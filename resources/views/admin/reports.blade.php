@extends('layouts.app')
@section('title', 'Kelola Laporan')

@section('content')
<h2 style="margin-bottom:16px;">Kelola Laporan Warga</h2>

<div class="card">
    <table>
        <thead>
            <tr><th>ID</th><th>Warga</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($reports as $r)
            <tr>
                <td>#{{ $r->id }}</td>
                <td>{{ $r->user->name ?? '-' }}</td>
                <td>
                    <strong>{{ $r->title }}</strong><br>
                    <span class="text-sm">{{ Str::limit($r->description, 40) }}</span>
                    @if($r->image_url)
                        <br><a href="{{ $r->image_url }}" target="_blank" style="font-size:0.72rem;color:#2563eb;">&#x1F4F7; Lihat Foto</a>
                    @endif
                </td>
                <td><span class="badge badge-gray">{{ $r->category }}</span></td>
                <td><span class="badge badge-{{ $r->status === 'resolved' ? 'green' : ($r->status === 'in_progress' ? 'blue' : 'yellow') }}">{{ $r->status }}</span></td>
                <td>
                    <form method="POST" action="/admin/reports/{{ $r->id }}/status" style="display:flex;gap:4px;">
                        @csrf
                        <select name="status" style="font-size:0.78rem;padding:4px 8px;">
                            <option value="open" {{ $r->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $r->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $r->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="font-size:0.72rem;padding:4px 8px;">Update</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="empty">Belum ada laporan</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $reports->links() }}
</div>
@endsection
