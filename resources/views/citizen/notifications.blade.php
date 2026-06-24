@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')
<h2 style="margin-bottom:16px;">Notifikasi</h2>
<div class="card">
    @forelse($notifications as $n)
        <div style="padding:12px 0;border-bottom:1px solid #e2e8f0;font-size:0.88rem;">
            <div>{{ $n->message }}</div>
            <div class="text-sm">{{ $n->created_at->diffForHumans() }}</div>
        </div>
    @empty
        <div class="empty">Belum ada notifikasi</div>
    @endforelse
    {{ $notifications->links() }}
</div>
@endsection
