@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div style="max-width:400px;margin:60px auto;">
    <div class="card">
        <h2>Login</h2>
        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="/login">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Login</button>
        </form>
    </div>
    <p class="text-sm" style="text-align:center;margin-top:12px;">Belum punya akun? <a href="/register">Register</a></p>
</div>
@endsection
