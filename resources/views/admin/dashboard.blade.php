@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<h2 style="margin-bottom:16px;">Dashboard Admin</h2>

<div class="grid-4 mb">
    <div class="card stat"><div class="stat-num">{{ $stats['users']['total'] }}</div><div class="stat-label">Total Pengguna</div></div>
    <div class="card stat"><div class="stat-num">{{ $stats['service_requests']['total'] }}</div><div class="stat-label">Permintaan Layanan</div></div>
    <div class="card stat"><div class="stat-num">{{ $stats['reports']['total'] }}</div><div class="stat-label">Laporan Warga</div></div>
    <div class="card stat"><div class="stat-num">{{ $stats['service_requests']['pending'] }}</div><div class="stat-label">Menunggu Diproses</div></div>
</div>

<div class="grid-2">
    <div class="card">
        <h2>Layanan</h2>
        <div style="font-size:0.88rem;">
            <div class="flex-between" style="padding:4px 0;"><span>Pending</span><span class="badge badge-yellow">{{ $stats['service_requests']['pending'] }}</span></div>
            <div class="flex-between" style="padding:4px 0;"><span>Processing</span><span class="badge badge-blue">{{ $stats['service_requests']['processing'] }}</span></div>
            <div class="flex-between" style="padding:4px 0;"><span>Done</span><span class="badge badge-green">{{ $stats['service_requests']['done'] }}</span></div>
            <div class="flex-between" style="padding:4px 0;"><span>Rejected</span><span class="badge badge-red">{{ $stats['service_requests']['rejected'] }}</span></div>
        </div>
    </div>
    <div class="card">
        <h2>Laporan</h2>
        <div style="font-size:0.88rem;">
            <div class="flex-between" style="padding:4px 0;"><span>Open</span><span class="badge badge-yellow">{{ $stats['reports']['open'] }}</span></div>
            <div class="flex-between" style="padding:4px 0;"><span>In Progress</span><span class="badge badge-blue">{{ $stats['reports']['in_progress'] }}</span></div>
            <div class="flex-between" style="padding:4px 0;"><span>Resolved</span><span class="badge badge-green">{{ $stats['reports']['resolved'] }}</span></div>
        </div>
    </div>
</div>

<div class="card" style="margin-top:16px;">
    <h2>Rekapitulasi Laporan per Kategori</h2>
    <table>
        <thead><tr><th>Kategori</th><th>Jumlah</th></tr></thead>
        <tbody>
            @foreach($reportsSummary as $s)
            <tr><td>{{ ucfirst($s->category) }}</td><td>{{ $s->total }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
