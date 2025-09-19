@extends('layouts.app')

@section('content')
<div class="text-center">
    <h1>Welcome to FoodMart 🎉</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger mt-3">Logout</button>
    </form>
</div>
@endsection
