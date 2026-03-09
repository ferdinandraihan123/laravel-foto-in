<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Foto.in') - Aplikasi Sewa Fotografi</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    
    <!-- Navbar Sederhana -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">Foto.in</a>
                    
                    <!-- Menu -->
                    <div class="hidden md:flex ml-10 space-x-8">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Dashboard</a>
                                <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Kasir</a>
                                <a href="{{ route('admin.kategori.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Kategori</a>
                                <a href="{{ route('admin.produk.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Produk</a>
                                <a href="{{ route('admin.transaksi.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Transaksi</a>
                                <a href="{{ route('admin.laporan.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Laporan</a>
                            @elseif(auth()->user()->isKasir())
                                <a href="{{ route('kasir.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Dashboard</a>
                                <a href="{{ route('kasir.produk.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Paket</a>
                                <a href="{{ route('kasir.transaksi.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Transaksi</a>
                            @elseif(auth()->user()->isOwner())
                                <a href="{{ route('owner.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Dashboard</a>
                                <a href="{{ route('owner.produk.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Paket</a>
                                <a href="{{ route('owner.laporan.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Laporan</a>
                                <a href="{{ route('owner.log-aktivitas.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Log</a>
                            @endif
                        @endauth
                    </div>
                </div>
                
                <!-- Logout -->
                <div class="flex items-center">
                    @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-red-600 text-sm font-medium">
                            Logout
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Content -->
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
    
    @stack('scripts')
</body>
</html>