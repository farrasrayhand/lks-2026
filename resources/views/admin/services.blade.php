@extends('layouts.app')
@section('title', 'Kelola Layanan')

@section('content')
<h2 style="margin-bottom:16px;">Kelola Permintaan Layanan</h2>

<div class="card">
    <table>
        <thead>
            <tr><th>ID</th><th>Warga</th><th>Layanan</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($requests as $r)
            <tr>
                <td>#{{ $r->id }}</td>
                <td>{{ $r->user->name ?? '-' }}</td>
                <td>
                    {{ $r->serviceType->name ?? '-' }}
                    @if($r->attachment_url)
                        <br><a href="{{ $r->attachment_url }}" target="_blank" style="font-size:0.72rem;color:#2563eb;">&#x1F4CE; Lampiran</a>
                    @endif
                </td>
                <td><span class="badge badge-{{ $r->status === 'done' ? 'green' : ($r->status === 'processing' ? 'blue' : ($r->status === 'rejected' ? 'red' : 'yellow')) }}">{{ $r->status }}</span></td>
                <td>{{ $r->created_at->format('d M Y') }}</td>
                <td>
                    <form method="POST" action="/admin/services/{{ $r->id }}/status" style="display:flex;gap:4px;">
                        @csrf
                        <select name="status" style="font-size:0.78rem;padding:4px 8px;">
                            <option value="pending" {{ $r->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $r->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="done" {{ $r->status === 'done' ? 'selected' : '' }}>Done</option>
                            <option value="rejected" {{ $r->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="font-size:0.72rem;padding:4px 8px;">Update</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="empty">Belum ada permintaan</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $requests->links() }}
</div>
@endsection
