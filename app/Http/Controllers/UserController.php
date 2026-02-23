<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan list user/kasir
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $users = User::whereIn('role', ['kasir', 'admin'])
            ->orderByRaw("FIELD(role, 'admin', 'kasir')")
            ->orderBy('status', 'desc')
            ->orderBy('name')
            ->paginate(10);
        
        LogAktivitas::catat('Melihat daftar user');
        
        return view('users.index', compact('users'));
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        return view('users.create');
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,kasir',
            'status' => 'required|in:aktif,nonaktif',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]);

        LogAktivitas::catat('Menambah user baru', "User: {$user->name}, Role: {$user->role}");

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Form edit user
     */
    public function edit(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        return view('users.edit', compact('user'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,kasir',
            'status' => 'required|in:aktif,nonaktif',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        LogAktivitas::catat('Mengupdate user', "User: {$user->name}");

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        if ($user->transaksis()->count() > 0) {
            return back()->with('error', 'User tidak bisa dihapus karena memiliki riwayat transaksi');
        }

        $nama = $user->name;
        $user->delete();

        LogAktivitas::catat('Menghapus user', "User: {$nama}");

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Toggle status user (aktif/nonaktif)
     */
    public function toggleStatus(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa mengubah status akun sendiri');
        }

        $statusBaru = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status' => $statusBaru]);

        LogAktivitas::catat('Mengubah status user', 
            "User: {$user->name}, Status: {$statusBaru}");

        return back()->with('success', 'Status user berhasil diubah');
    }
}