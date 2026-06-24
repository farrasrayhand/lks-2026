<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|in:infrastructure,environment,social,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validator->errors(),
            ], 422);
        }

        $imageUrl = $request->image_url;
        if ($request->hasFile('image')) {
            $imageUrl = Storage::disk('uploads')->url(
                $request->file('image')->store('reports', 'uploads')
            );
        }

        $report = Report::create([
            'user_id' => auth()->id(),
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'image_url' => $imageUrl,
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully',
            'data' => $report,
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $query = Report::with('user')->latest();
        } else {
            $query = Report::with('user')->where('user_id', $user->id)->latest();
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $perPage = min((int) $request->get('per_page', 10), 100);
        $reports = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Reports retrieved',
            'data' => $reports,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $user = auth()->user();
        $report = Report::with('user')->find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found',
                'data' => null,
            ], 404);
        }

        if (!$user->isAdmin() && $report->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Report retrieved',
            'data' => $report,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found',
                'data' => null,
            ], 404);
        }

        $user = auth()->user();

        if (!$user->isAdmin() && $report->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null,
            ], 403);
        }

        $rules = [
            'category' => 'sometimes|in:infrastructure,environment,social,other',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'location' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'status' => 'sometimes|in:open,in_progress,resolved',
        ];

        if (!$user->isAdmin()) {
            unset($rules['status']);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validator->errors(),
            ], 422);
        }

        $report->update($request->only(array_keys($rules)));

        return response()->json([
            'success' => true,
            'message' => 'Report updated',
            'data' => $report->load('user'),
        ]);
    }
}
