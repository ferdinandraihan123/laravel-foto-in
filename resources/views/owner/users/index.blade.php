@extends('layouts.app')

@section('title','Daftar User')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Daftar User</h1>
        <p class="text-gray-600 mt-1">Owner hanya dapat melihat data user</p>
    </div>

    {{-- SEARCH & FILTER --}}
    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-100">

        <form action="{{ route('owner.users.index') }}" method="GET" class="flex flex-wrap gap-4">

            {{-- SEARCH --}}
            <div class="flex-1 min-w-[200px]">

                <div class="relative">

                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input type="text" name="search" placeholder="Cari nama/email..." value="{{ request('search') }}" class="w-full pl-10 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">

                </div>

            </div>

            {{-- FILTER ROLE --}}
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

            {{-- FILTER STATUS --}}
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

            {{-- BUTTON FILTER --}}
            <div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">

                    Filter

                </button>

            </div>

            {{-- RESET --}}
            @if(request()->anyFilled(['search','role','status']))
            <div>

                <a href="{{ route('owner.users.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 inline-block">

                    Reset

                </a>

            </div>
            @endif

        </form>

    </div>


    {{-- TABLE --}}
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

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No HP</th>

                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @foreach($users as $user)

                    <tr>

                        <td class="px-6 py-4 text-sm">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->email }}
                        </td>

                        <td class="px-6 py-4 text-sm">

                            <span class="px-2 py-1 rounded-full text-xs
{{ $user->role=='admin'
? 'bg-purple-100 text-purple-800'
: ($user->role=='kasir'
? 'bg-blue-100 text-blue-800'
: 'bg-green-100 text-green-800') }}">

                                {{ ucfirst($user->role) }}

                            </span>

                        </td>

                        <td class="px-6 py-4 text-sm">

                            <span class="px-2 py-1 rounded-full text-xs
{{ $user->status=='aktif'
? 'bg-green-100 text-green-800'
: 'bg-red-100 text-red-800' }}">

                                {{ ucfirst($user->status) }}

                            </span>

                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->no_hp ?? '-' }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
