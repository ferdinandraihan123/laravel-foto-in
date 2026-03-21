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

    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <div class="w-16 h-16 rounded-full overflow-hidden">
                            <img src="{{ asset('storage/logo.png') }}" alt="Foto.in" class="w-full h-full object-cover">
                        </div>
                    </a>

                    <div class="hidden md:flex space-x-8 ml-4">
                        @auth
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-blue-600  py-2 text-sm font-medium">Kasir</a>
                        <a href="{{ route('admin.kategori.index') }}" class="text-gray-700 hover:text-blue-600  py-2 text-sm font-medium">Kategori</a>
                        <a href="{{ route('admin.produk.index') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">Produk</a>
                        <a href="{{ route('admin.transaksi.index') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">Transaksi</a>
                        <a href="{{ route('admin.laporan.index') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">Laporan</a>
                        @elseif(auth()->user()->isKasir())
                        <a href="{{ route('kasir.dashboard') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">Dashboard</a>
                        <a href="{{ route('kasir.produk.index') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">Paket</a>
                        @elseif(auth()->user()->isOwner())
                        <a href="{{ route('owner.dashboard') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">Dashboard</a>
                        <a href="{{ route('owner.users.index') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">User</a>
                        <a href="{{ route('owner.produk.index') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">Paket</a>
                        <a href="{{ route('owner.laporan.index') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">Laporan</a>
                        <a href="{{ route('owner.log-aktivitas.index') }}" class="text-gray-700 hover:text-blue-600 py-2 text-sm font-medium">Log</a>
                        @endif
                        @endauth
                    </div>
                </div>

                <div class="flex items-center justify-end ">
                    @auth
                    <button onclick="confirmLogout()" class="text-gray-700 hover:text-red-600 text-sm font-medium">
                        Logout
                    </button>

                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
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

    @stack('scripts')

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Yakin ingin logout?'
                , text: 'Session kamu akan berakhir!'
                , showCancelButton: true
                , confirmButtonText: 'Ya, yakin'
                , cancelButtonText: 'Batal'
                , reverseButtons: true
                , imageUrl: "{{ asset('storage/logo.png') }}"
                , imageWidth: 100
                , imageHeight: 100
                , imageAlt: 'Logout icon'
                , imagePadding: '0 0 15px 0'
                , buttonsStyling: false
                , customClass: {
                    cancelButton: 'inline-flex items-center justify-center px-6 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 mr-3'
                    , confirmButton: 'inline-flex items-center justify-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700'
                    , image: 'mb-4 mx-auto', // margin bottom biar nggak nempel ke teks
                    title: 'text-xl font-semibold text-gray-800 mb-2'
                    , htmlContainer: 'text-gray-600 mb-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

    </script>
</body>
</html>
