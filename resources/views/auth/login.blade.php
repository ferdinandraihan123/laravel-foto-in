@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="flex justify-center">
                <div class="relative">
                    <div class="w-28 h-28 bg-white rounded-3xl flex items-center justify-center shadow-xl transfor">
                        <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="w-full h-full object-contain p-2">
                    </div>
                </div>
            </div>
            <div>
                <p class="mt-2 text-sm text-gray-600 max-w-xs mx-auto">
                    <span class="font-semibold text-blue-700">Capture Your Story</span>
                </p>
                <p class="mt-2 text-xs text-gray-500">
                    Aplikasi Manajemen Jasa Fotografi
                </p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 mt-8">
            <form class="space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="flex items-center">
                            Email:
                        </span>
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required autofocus
                           value="{{ old('email') }}"
                           class="appearance-none relative block w-full px-4 py-3 border rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition duration-150 ease-in-out @error('email') border-red-500 @enderror"
                           placeholder="Masukan Email...">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="flex items-center">
                            Password:
                        </span>
                    </label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="appearance-none relative block w-full px-4 py-3 border rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition duration-150 ease-in-out @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-blue-700 to-blue-600 hover:from-blue-800 hover:to-blue-700">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3"></span>
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection