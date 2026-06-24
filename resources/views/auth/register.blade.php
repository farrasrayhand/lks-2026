@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div style="max-width:400px;margin:60px auto;">
    <div class="card">
        <h2>Register</h2>
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
            </div>
        @endif
        <form method="POST" action="/register">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" required value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <div class="form-group">
                <label>Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}">
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="address" rows="2">{{ old('address') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Register</button>
        </form>
    </div>
    <p class="text-sm" style="text-align:center;margin-top:12px;">Sudah punya akun? <a href="/login">Login</a></p>
</div>
@endsection
