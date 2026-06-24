@extends('layouts.app')
@section('title', 'Layanan Publik')

@section('content')
<div class="flex-between mb">
    <h2>Permintaan Layanan</h2>
    <button class="btn btn-primary" onclick="document.getElementById('requestForm').style.display='block'">+ Ajukan Layanan</button>
</div>

<div class="card" id="requestForm" style="display:none;margin-bottom:16px;">
    <h2>Ajukan Layanan Baru</h2>
    <form method="POST" action="/citizen/services" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Jenis Layanan</label>
            <select name="service_type_id" required>
                <option value="">-- Pilih Layanan --</option>
                @foreach($serviceTypes as $st)
                    <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->estimated_days }} hari)</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label>Lampiran (PDF/JPG, max 5MB)</label>
            <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf">
        </div>
        <button type="submit" class="btn btn-primary">Kirim Permintaan</button>
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('requestForm').style.display='none'">Batal</button>
    </form>
</div>

<div class="card">
    <table>
        <thead>
            <tr><th>Layanan</th><th>Status</th><th>Tanggal</th><th></th></tr>
        </thead>
        <tbody>
            @forelse($requests as $r)
            <tr>
                <td>
                    <strong>{{ $r->serviceType->name ?? '-' }}</strong><br>
                    <span class="text-sm">{{ Str::limit($r->description, 50) }}</span>
                    @if($r->attachment_url)
                        <br><a href="{{ $r->attachment_url }}" target="_blank" style="font-size:0.75rem;color:#2563eb;">&#x1F4CE; Lihat Lampiran</a>
                    @endif
                </td>
                <td><span class="badge badge-{{ $r->status === 'done' ? 'green' : ($r->status === 'processing' ? 'blue' : ($r->status === 'rejected' ? 'red' : 'yellow')) }}">{{ $r->status }}</span></td>
                <td>{{ $r->created_at->format('d M Y') }}</td>
                <td><a href="/citizen/services/{{ $r->id }}" class="btn btn-secondary" style="font-size:0.78rem;">Detail</a></td>
            </tr>
            @empty
            <tr><td colspan="4" class="empty">Belum ada permintaan layanan</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $requests->links() }}
</div>
@endsection
