@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="category-item p-5">
                <h2 class="mb-4 text-center">Enter OTP</h2>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('2fa.verify') }}">
                    @csrf
                    <div class="mb-3">
                        <label>OTP</label>
                        <input type="text" name="otp" class="form-control" required>
                        @error('otp') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
