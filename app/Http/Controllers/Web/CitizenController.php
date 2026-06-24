<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Report;
use App\Models\ServiceRequest;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CitizenController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        return view('citizen.dashboard', [
            'stats' => [
                'my_requests' => ServiceRequest::where('user_id', $user->id)->count(),
                'my_reports' => Report::where('user_id', $user->id)->count(),
                'unread_notif' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
            ],
            'recentRequests' => ServiceRequest::with('serviceType')
                ->where('user_id', $user->id)->latest()->take(5)->get(),
            'recentReports' => Report::where('user_id', $user->id)->latest()->take(5)->get(),
        ]);
    }

    public function services()
    {
        $user = auth()->user();

        return view('citizen.services', [
            'serviceTypes' => ServiceType::all(),
            'requests' => ServiceRequest::with('serviceType')
                ->where('user_id', $user->id)->latest()->paginate(10),
        ]);
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'description' => 'nullable|string',
            'attachment' => 'sometimes|nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $attachmentUrl = null;
        if ($request->hasFile('attachment')) {
            $attachmentUrl = Storage::disk('uploads')->url(
                $request->file('attachment')->store('services', 'uploads')
            );
        }

        ServiceRequest::create([
            'user_id' => auth()->id(),
            'service_type_id' => $validated['service_type_id'],
            'description' => $validated['description'],
            'attachment_url' => $attachmentUrl,
            'status' => 'pending',
        ]);

        return redirect('/citizen/services')->with('success', 'Permintaan layanan berhasil diajukan!');
    }

    public function reports()
    {
        $user = auth()->user();

        return view('citizen.reports', [
            'reports' => Report::where('user_id', $user->id)->latest()->paginate(10),
        ]);
    }

    public function storeReport(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:infrastructure,environment,social,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'sometimes|nullable|string|max:255',
            'image' => 'sometimes|nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = Storage::disk('uploads')->url(
                $request->file('image')->store('reports', 'uploads')
            );
        }

        Report::create([
            'user_id' => auth()->id(),
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'] ?? null,
            'image_url' => $imageUrl,
            'status' => 'open',
        ]);

        return redirect('/citizen/reports')->with('success', 'Laporan berhasil dikirim!');
    }

    public function showService(int $id)
    {
        $user = auth()->user();
        $serviceRequest = ServiceRequest::with(['serviceType', 'user'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('citizen.service-detail', compact('serviceRequest'));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()->paginate(10);

        // Mark as read
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('citizen.notifications', compact('notifications'));
    }
}
