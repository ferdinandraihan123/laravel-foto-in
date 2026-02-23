<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Foto.in') - Aplikasi Sewa Fotografi</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                       <div class="w-20 h-20 rounded-3xl flex items-center justify-center">
                            <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="w-full h-full object-contain p-2">
                        </div>
                        <span class="text-xl font-semibold text-gray-700">Foto.in</span>
                    </a>
                    
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->user()->isKasir())
                                <a href="{{ route('transaksis.index') }}" class="text-gray-700 hover:text-blue-700 px-3 py-2 text-sm font-medium">
                                    Transaksi
                                </a>
                            @endif
                            
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-700 px-3 py-2 text-sm font-medium">
                                    Paket
                                </a>
                                <a href="{{ route('kategoris.index') }}" class="text-gray-700 hover:text-blue-700 px-3 py-2 text-sm font-medium">
                                    Kategori
                                </a>
                                <a href="{{ route('users.index') }}" class="text-gray-700 hover:text-blue-700 px-3 py-2 text-sm font-medium">
                                    Kelola Kasir
                                </a>
                            @endif
                            
                            @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                                <a href="{{ route('laporan.index') }}" class="text-gray-700 hover:text-blue-700 px-3 py-2 text-sm font-medium">
                                    Laporan
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-blue-700">
                                <span>{{ Auth::user()->name }}</span>
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <main class="py-6">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            </div>
        @endif
        
        @if(session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg">
                    {{ session('info') }}
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>