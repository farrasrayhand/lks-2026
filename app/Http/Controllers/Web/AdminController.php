<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Report;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalCitizens = User::where('role', 'citizen')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalReports = Report::count();
        $totalServiceRequests = ServiceRequest::count();

        $stats = [
            'users' => [
                'total' => $totalUsers,
                'citizens' => $totalCitizens,
                'admins' => $totalAdmins,
            ],
            'reports' => [
                'total' => $totalReports,
                'open' => Report::where('status', 'open')->count(),
                'in_progress' => Report::where('status', 'in_progress')->count(),
                'resolved' => Report::where('status', 'resolved')->count(),
            ],
            'service_requests' => [
                'total' => $totalServiceRequests,
                'pending' => ServiceRequest::where('status', 'pending')->count(),
                'processing' => ServiceRequest::where('status', 'processing')->count(),
                'done' => ServiceRequest::where('status', 'done')->count(),
                'rejected' => ServiceRequest::where('status', 'rejected')->count(),
            ],
        ];

        $reportsSummary = Report::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')->get();

        return view('admin.dashboard', compact('stats', 'reportsSummary'));
    }

    public function services()
    {
        return view('admin.services', [
            'requests' => ServiceRequest::with(['user', 'serviceType'])->latest()->paginate(10),
        ]);
    }

    public function updateServiceStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,done,rejected',
        ]);

        $sr = ServiceRequest::with('serviceType')->findOrFail($id);
        $oldStatus = $sr->status;
        $sr->update(['status' => $validated['status']]);

        if ($oldStatus !== $validated['status']) {
            $statusLabel = [
                'pending' => 'Pending',
                'processing' => 'Sedang Diproses',
                'done' => 'Selesai',
                'rejected' => 'Ditolak',
            ];

            Notification::create([
                'user_id' => $sr->user_id,
                'message' => 'Status layanan ' . $sr->serviceType->name . ' berubah menjadi ' . $statusLabel[$validated['status']],
                'type' => 'service_status',
                'reference_id' => $sr->id,
                'reference_type' => 'service_request',
            ]);
        }

        return back()->with('success', 'Status berhasil diupdate!');
    }

    public function reports()
    {
        return view('admin.reports', [
            'reports' => Report::with('user')->latest()->paginate(10),
        ]);
    }

    public function updateReportStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
        ]);

        $report = Report::findOrFail($id);
        $report->update(['status' => $validated['status']]);

        return back()->with('success', 'Status laporan berhasil diupdate!');
    }
}
