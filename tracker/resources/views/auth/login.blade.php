@extends('layout')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
    
    <form action="{{ route('login') }}" method="POST" class="bg-white p-6 rounded shadow-md border">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
        
        <p class="mt-4 text-center text-sm">
            Don't have an account? <a href="{{ route('register') }}" class="text-blue-500">Register</a>
        </p>
    </form>
</div>
@endsection
