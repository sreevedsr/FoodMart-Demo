@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="category-item p-5">
                <h2 class="mb-4 text-center">Login to FoodMart</h2>
    
                <form method="POST" action="{{ route('login') }}">
                    @csrf
    
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
    
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
    
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
    
                <p class="mt-3 text-center">
                    Don’t have an account? <a href="{{ route('signup') }}">Sign Up</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
