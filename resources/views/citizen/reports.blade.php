@extends('layouts.app')
@section('title', 'Laporan Warga')

@section('content')
<div class="flex-between mb">
    <h2>Laporan Warga</h2>
    <button class="btn btn-primary" onclick="document.getElementById('reportForm').style.display='block'">+ Buat Laporan</button>
</div>

<div class="card" id="reportForm" style="display:none;margin-bottom:16px;">
    <h2>Buat Laporan Baru</h2>
    <form method="POST" action="/citizen/reports" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Kategori</label>
            <select name="category" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="infrastructure">Infrastruktur</option>
                <option value="environment">Lingkungan</option>
                <option value="social">Sosial</option>
                <option value="other">Lainnya</option>
            </select>
        </div>
        <div class="form-group">
            <label>Judul</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" name="location">
        </div>
        <div class="form-group">
            <label>Foto (JPG/PNG, max 5MB)</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png">
        </div>
        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('reportForm').style.display='none'">Batal</button>
    </form>
</div>

<div class="card">
    <table>
        <thead>
            <tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Tanggal</th></tr>
        </thead>
        <tbody>
            @forelse($reports as $r)
            <tr>
                <td>
                    <strong>{{ $r->title }}</strong><br>
                    <span class="text-sm">{{ Str::limit($r->description, 60) }}</span>
                    @if($r->image_url)
                        <br><a href="{{ $r->image_url }}" target="_blank" style="font-size:0.75rem;color:#2563eb;">&#x1F4F7; Lihat Foto</a>
                    @endif
                </td>
                <td><span class="badge badge-gray">{{ $r->category }}</span></td>
                <td><span class="badge badge-{{ $r->status === 'resolved' ? 'green' : ($r->status === 'in_progress' ? 'blue' : 'yellow') }}">{{ $r->status }}</span></td>
                <td>{{ $r->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="empty">Belum ada laporan</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $reports->links() }}
</div>
@endsection
