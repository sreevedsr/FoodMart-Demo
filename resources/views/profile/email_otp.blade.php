@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Enter OTP to Verify Email</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <div class="mb-3">
            <label>OTP</label>
            <input type="text" name="otp" class="form-control" required>
            @error('otp') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
    </form>
</div>
@endsection
