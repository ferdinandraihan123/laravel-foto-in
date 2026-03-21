<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Hanya admin yang bisa mengelola kategori.');
        }

        $query = Kategori::query();

        if ($request->filled('search')) {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('status', 'desc')->orderBy('nama_kategori');

        $kategoris = $query->paginate(10)->withQueryString();

        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $kategori = Kategori::create($request->all());

        LogAktivitas::catat('Menambah kategori', "Kategori: {$kategori->nama_kategori}");

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Kategori $kategori)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $kategori->id_kategori . ',id_kategori',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $kategori->update($request->all());

        LogAktivitas::catat('Mengupdate kategori', "Kategori: {$kategori->nama_kategori}");

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Kategori $kategori)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        if ($kategori->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        LogAktivitas::catat('Menghapus kategori', "Kategori: {$nama}");

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}