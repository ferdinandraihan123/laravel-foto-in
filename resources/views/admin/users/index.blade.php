@extends('layouts.app')

@section('title', 'Kelola Kasir')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Kasir</h1>
            <p class="text-gray-600 mt-1">Daftar semua user dan kasir</p>
        </div>

        <a href="{{ route('admin.users.create') }}" class="bg-blue-700 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition shadow-md flex items-center">
            Tambah User
        </a>
    </div>


    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-100">

        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4">

            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari nama/email..." value="{{ request('search') }}" class="w-full pl-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
                </div>
            </div>


            <div class="w-40">

                <select name="role" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">

                    <option value="">Semua Role</option>

                    <option value="admin" {{ request('role')=='admin'?'selected':'' }}>
                        Admin
                    </option>

                    <option value="kasir" {{ request('role')=='kasir'?'selected':'' }}>
                        Kasir
                    </option>

                </select>

            </div>


            <div class="w-40">

                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">

                    <option value="">Semua Status</option>

                    <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>
                        Aktif
                    </option>

                    <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>
                        Nonaktif
                    </option>

                </select>

            </div>


            <div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">

                    Filter

                </button>

            </div>


            @if(request()->anyFilled(['search','role','status']))
            <div>

                <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 inline-block">

                    Reset

                </a>

            </div>
            @endif

        </form>

    </div>



    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>



                <tbody class="bg-white divide-y divide-gray-200">

                    @forelse($users as $user)

                    <tr>

                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $users->firstItem() + $loop->index }}
                        </td>

                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->email }}
                        </td>


                        <td class="px-6 py-4 text-sm">

                            <span class="px-2 py-1 rounded-full text-sm {{ $user->role == 'admin'}}">
                                {{ ucfirst($user->role) }}
                            </span>

                        </td>


                        <td class="px-6 py-4 text-sm">

                            <span class="px-2 py-1 rounded-full text-sm {{ $user->status=='aktif' ? : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($user->status) }}
                            </span>

                        </td>


                        <td class="px-6 py-4 text-sm space-x-2">

                            <a href="{{ route('admin.users.edit',$user) }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">

                                Edit

                            </a>


                            <form action="{{ route('admin.users.toggleStatus',$user) }}" method="POST" class="inline">

                                @csrf
                                @method('PUT')

                                <button type="submit" class="text-{{ $user->status=='aktif' ? 'yellow' : 'green' }}-600 hover:underline">

                                    {{ $user->status=='aktif' ? 'Nonaktifkan' : 'Aktifkan' }}

                                </button>

                            </form>


                            @if($user->id != auth()->id())

                            <form action="{{ route('admin.users.destroy',$user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">

                                @csrf
                                @method('DELETE')

                                <button class="text-red-600 hover:text-red-800 hover:underline">
                                    Hapus
                                </button>

                            </form>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6" class="text-center py-6 text-gray-500">

                            Data user tidak ditemukan

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>



        <div class="px-6 py-4 border-t border-gray-200">

            {{ $users->appends(request()->query())->links() }}

        </div>

    </div>

</div>
@endsection
