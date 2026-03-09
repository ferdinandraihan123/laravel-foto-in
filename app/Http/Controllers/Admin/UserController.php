<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['kasir', 'admin']);

        // SEARCH
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // FILTER ROLE
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query
            ->orderByRaw("FIELD(role, 'admin', 'kasir')")
            ->orderBy('status', 'desc')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString(); // supaya pagination tetap membawa filter

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
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

        LogAktivitas::catat('Menambah user', "User: {$user->name}, Role: {$user->role}");

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
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

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        if ($user->transaksis()->count() > 0) {
            return back()->with('error', 'User tidak bisa dihapus karena memiliki riwayat transaksi');
        }

        $nama = $user->name;
        $user->delete();

        LogAktivitas::catat('Menghapus user', "User: {$nama}");

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa mengubah status akun sendiri');
        }

        $user->status = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        LogAktivitas::catat('Mengubah status user', 
            "User: {$user->name}, Status: {$user->status}");

        return back()->with('success', 'Status user berhasil diubah');
    }
}