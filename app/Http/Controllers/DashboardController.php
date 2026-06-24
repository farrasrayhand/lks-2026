<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalUsers = User::count();
        $totalCitizens = User::where('role', 'citizen')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalReports = Report::count();
        $openReports = Report::where('status', 'open')->count();
        $inProgressReports = Report::where('status', 'in_progress')->count();
        $resolvedReports = Report::where('status', 'resolved')->count();
        $totalServiceRequests = ServiceRequest::count();
        $pendingRequests = ServiceRequest::where('status', 'pending')->count();
        $processingRequests = ServiceRequest::where('status', 'processing')->count();
        $doneRequests = ServiceRequest::where('status', 'done')->count();
        $rejectedRequests = ServiceRequest::where('status', 'rejected')->count();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard statistics retrieved',
            'data' => [
                'users' => [
                    'total' => $totalUsers,
                    'citizens' => $totalCitizens,
                    'admins' => $totalAdmins,
                ],
                'reports' => [
                    'total' => $totalReports,
                    'open' => $openReports,
                    'in_progress' => $inProgressReports,
                    'resolved' => $resolvedReports,
                ],
                'service_requests' => [
                    'total' => $totalServiceRequests,
                    'pending' => $pendingRequests,
                    'processing' => $processingRequests,
                    'done' => $doneRequests,
                    'rejected' => $rejectedRequests,
                ],
            ],
        ]);
    }

    public function reportsSummary(): JsonResponse
    {
        $summary = Report::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Reports summary by category retrieved',
            'data' => $summary,
        ]);
    }
}
