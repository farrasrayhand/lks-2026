@extends('layouts.app')
@section('title', 'Dashboard Warga')

@section('content')
<h2 style="margin-bottom:16px;">Dashboard Warga</h2>
<div class="grid-3 mb">
    <div class="card stat">
        <div class="stat-num">{{ $stats['my_requests'] ?? 0 }}</div>
        <div class="stat-label">Permintaan Layanan</div>
    </div>
    <div class="card stat">
        <div class="stat-num">{{ $stats['my_reports'] ?? 0 }}</div>
        <div class="stat-label">Laporan Saya</div>
    </div>
    <div class="card stat">
        <div class="stat-num">{{ $stats['unread_notif'] ?? 0 }}</div>
        <div class="stat-label">Notifikasi Baru</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h2>Layanan Terbaru</h2>
        @forelse($recentRequests ?? [] as $r)
            <div style="padding:8px 0;border-bottom:1px solid #e2e8f0;font-size:0.88rem;">
                <strong>{{ $r->serviceType->name ?? 'Layanan' }}</strong>
                <span class="badge badge-{{ $r->status === 'done' ? 'green' : ($r->status === 'processing' ? 'blue' : ($r->status === 'rejected' ? 'red' : 'yellow')) }}">{{ $r->status }}</span>
                <div class="text-sm">{{ $r->created_at->diffForHumans() }}</div>
            </div>
        @empty
            <div class="empty">Belum ada permintaan layanan</div>
        @endforelse
        <a href="/citizen/services" class="btn btn-secondary" style="margin-top:12px;">Lihat Semua</a>
    </div>
    <div class="card">
        <h2>Laporan Terbaru</h2>
        @forelse($recentReports ?? [] as $r)
            <div style="padding:8px 0;border-bottom:1px solid #e2e8f0;font-size:0.88rem;">
                <strong>{{ $r->title }}</strong>
                <span class="badge badge-{{ $r->status === 'resolved' ? 'green' : ($r->status === 'in_progress' ? 'blue' : 'yellow') }}">{{ $r->status }}</span>
                <div class="text-sm">{{ $r->created_at->diffForHumans() }}</div>
            </div>
        @empty
            <div class="empty">Belum ada laporan</div>
        @endforelse
        <a href="/citizen/reports" class="btn btn-secondary" style="margin-top:12px;">Lihat Semua</a>
    </div>
</div>
@endsection
