<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\ServiceRequest;
use App\Models\ServiceType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = ServiceType::all();

        return response()->json([
            'success' => true,
            'message' => 'Service types retrieved',
            'data' => $services,
        ]);
    }

    public function request(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'service_type_id' => 'required|exists:service_types,id',
            'description' => 'nullable|string',
            'attachment_url' => 'nullable|url',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validator->errors(),
            ], 422);
        }

        $attachmentUrl = $request->attachment_url;
        if ($request->hasFile('attachment')) {
            $attachmentUrl = Storage::disk('uploads')->url(
                $request->file('attachment')->store('services', 'uploads')
            );
        }

        $serviceRequest = ServiceRequest::create([
            'user_id' => auth()->id(),
            'service_type_id' => $request->service_type_id,
            'description' => $request->description,
            'attachment_url' => $attachmentUrl,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service request submitted',
            'data' => $serviceRequest->load('serviceType'),
        ], 201);
    }

    public function showRequest(int $id): JsonResponse
    {
        $user = auth()->user();
        $query = ServiceRequest::with(['user', 'serviceType']);

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $serviceRequest = $query->find($id);

        if (!$serviceRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Service request not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service request retrieved',
            'data' => $serviceRequest,
        ]);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,done,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validator->errors(),
            ], 422);
        }

        $serviceRequest = ServiceRequest::with('serviceType')->find($id);

        if (!$serviceRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Service request not found',
                'data' => null,
            ], 404);
        }

        $oldStatus = $serviceRequest->status;
        $serviceRequest->update(['status' => $request->status]);

        if ($oldStatus !== $request->status) {
            $statusLabel = [
                'pending' => 'Pending',
                'processing' => 'Sedang Diproses',
                'done' => 'Selesai',
                'rejected' => 'Ditolak',
            ];

            Notification::create([
                'user_id' => $serviceRequest->user_id,
                'message' => 'Status layanan ' . $serviceRequest->serviceType->name . ' berubah menjadi ' . $statusLabel[$request->status],
                'type' => 'service_status',
                'reference_id' => $serviceRequest->id,
                'reference_type' => 'service_request',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service request status updated',
            'data' => $serviceRequest->load(['user', 'serviceType']),
        ]);
    }

    public function listRequests(Request $request): JsonResponse
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $query = ServiceRequest::with(['user', 'serviceType'])->latest();
        } else {
            $query = ServiceRequest::with('serviceType')
                ->where('user_id', $user->id)
                ->latest();
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $perPage = min((int) $request->get('per_page', 10), 100);
        $requests = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Service requests retrieved',
            'data' => $requests,
        ]);
    }
}
