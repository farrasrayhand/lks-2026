@extends('layouts.app')
@section('title', 'Detail Layanan')

@section('content')
<div class="flex-between mb">
    <h2>Detail Permintaan Layanan #{{ $serviceRequest->id }}</h2>
    <a href="/citizen/services" class="btn btn-secondary">Kembali</a>
</div>

<div class="grid-2">
    <div class="card">
        <h2>Informasi Layanan</h2>
        <div style="font-size:0.9rem;line-height:2;">
            <div><strong>Jenis Layanan:</strong> {{ $serviceRequest->serviceType->name ?? '-' }}</div>
            <div><strong>Estimasi:</strong> {{ $serviceRequest->serviceType->estimated_days ?? '-' }} hari</div>
            <div><strong>Status:</strong>
                <span class="badge badge-{{ $serviceRequest->status === 'done' ? 'green' : ($serviceRequest->status === 'processing' ? 'blue' : ($serviceRequest->status === 'rejected' ? 'red' : 'yellow')) }}">
                    {{ $serviceRequest->status }}
                </span>
            </div>
            <div><strong>Tanggal Ajuan:</strong> {{ $serviceRequest->created_at->format('d M Y H:i') }}</div>
            <div><strong>Terakhir Update:</strong> {{ $serviceRequest->updated_at->format('d M Y H:i') }}</div>
        </div>
    </div>

    <div class="card">
        <h2>Deskripsi</h2>
        <p style="font-size:0.9rem;line-height:1.6;">{{ $serviceRequest->description ?: 'Tidak ada deskripsi' }}</p>

        @if($serviceRequest->attachment_url)
        <div style="margin-top:16px;">
            <strong style="font-size:0.85rem;">Lampiran:</strong>
            <div style="margin-top:6px;">
                @php $ext = pathinfo($serviceRequest->attachment_url, PATHINFO_EXTENSION); @endphp
                @if(in_array($ext, ['jpg','jpeg','png']))
                    <img src="{{ $serviceRequest->attachment_url }}" alt="Lampiran" style="max-width:100%;border-radius:6px;border:1px solid #e2e8f0;">
                @else
                    <a href="{{ $serviceRequest->attachment_url }}" target="_blank" class="btn btn-primary">&#x1F4CE; Download Lampiran</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<div class="card" style="margin-top:16px;">
    <h2>Status History</h2>
    <div style="display:flex;gap:24px;align-items:center;padding:12px 0;">
        @php $statuses = ['pending','processing','done']; $colors = ['yellow','blue','green']; @endphp
        @foreach($statuses as $i => $s)
            <div style="text-align:center;flex:1;">
                <div style="width:32px;height:32px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;
                    background:{{ in_array($serviceRequest->status, array_slice($statuses, $i)) ? '#2563eb' : '#e2e8f0' }};
                    color:{{ in_array($serviceRequest->status, array_slice($statuses, $i)) ? '#fff' : '#94a3b8' }};">
                    {{ $i + 1 }}
                </div>
                <div style="font-size:0.75rem;margin-top:4px;font-weight:{{ $serviceRequest->status === $s ? '600' : '400' }};">
                    {{ ucfirst($s === 'pending' ? 'Pending' : ($s === 'processing' ? 'Diproses' : 'Selesai')) }}
                </div>
            </div>
            @if($i < 2)
                <div style="flex:0.5;height:2px;background:{{ in_array($serviceRequest->status, array_slice($statuses, $i+1)) ? '#2563eb' : '#e2e8f0' }};"></div>
            @endif
        @endforeach
    </div>
    @if($serviceRequest->status === 'rejected')
        <div style="text-align:center;padding:8px;margin-top:8px;">
            <span class="badge badge-red" style="font-size:0.9rem;">Ditolak</span>
        </div>
    @endif
</div>
@endsection
