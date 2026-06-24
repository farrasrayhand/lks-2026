<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    public function page()
    {
        $result = $this->runChecks();
        return view('health', ['checks' => $result['data']]);
    }

    public function check(): JsonResponse
    {
        $result = $this->runChecks();
        return response()->json($result, $result['success'] ? 200 : 503);
    }

    private function runChecks(): array
    {
        $checks = [
            'app' => true,
            'timestamp' => now()->toIso8601String(),
        ];

        try {
            DB::connection()->getPdo();
            $checks['database'] = ['status' => 'ok', 'connection' => DB::connection()->getName()];
        } catch (\Throwable $e) {
            $checks['database'] = ['status' => 'error', 'message' => $e->getMessage()];
        }

        try {
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'ok', 10);
            $value = Cache::get($testKey);
            Cache::forget($testKey);
            $checks['cache'] = ['status' => $value === 'ok' ? 'ok' : 'error', 'driver' => config('cache.default')];
        } catch (\Throwable $e) {
            $checks['cache'] = ['status' => 'error', 'message' => $e->getMessage()];
        }

        try {
            $disk = Storage::disk('uploads');
            $testFile = '.health_check_test';
            $disk->put($testFile, 'ok');
            $content = $disk->get($testFile);
            $disk->delete($testFile);
            $checks['storage'] = [
                'status' => $content === 'ok' ? 'ok' : 'error',
                'disk' => config('filesystems.default'),
            ];
        } catch (\Throwable $e) {
            $checks['storage'] = ['status' => 'error', 'message' => $e->getMessage()];
        }

        $allOk = true;
        foreach (['database', 'cache', 'storage'] as $c) {
            if (($checks[$c]['status'] ?? 'error') !== 'ok') {
                $allOk = false;
            }
        }

        return [
            'success' => $allOk,
            'message' => $allOk ? 'All systems operational' : 'Some services are degraded',
            'data' => $checks,
        ];
    }
}
