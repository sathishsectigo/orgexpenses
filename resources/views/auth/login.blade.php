@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row">
    <div class="col-md-6 d-none d-md-block">
        <img src="{{ asset('images/login-bg.jpg') }}" class="img-fluid" alt="Banner">
    </div>
    <div class="col-md-6">
        <h3 class="text-center">Login</h3>
        <form method="POST" action="{{ route('auth.otp.generate') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send OTP</button>
        </form>
    </div>
</div>
@endsection
