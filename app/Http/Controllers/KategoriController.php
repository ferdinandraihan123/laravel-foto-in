<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Constructor untuk middleware
     */
    public function index()
    {
        // Cek akses manual
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access. Hanya admin yang bisa mengelola kategori.');
        }

        $kategoris = Kategori::orderBy('status', 'desc')
            ->orderBy('nama_kategori')
            ->paginate(10);

        return view('kategoris.index', compact('kategoris'));
    }


    /**
     * Form tambah kategori
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        return view('kategoris.create');
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $kategori = Kategori::create($request->all());

        LogAktivitas::catat('Menambah kategori', "Kategori: {$kategori->nama_kategori}");

        return redirect()->route('kategoris.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Form edit kategori
     */
    public function edit(Kategori $kategori)
    {
        return view('kategoris.edit', compact('kategori'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $kategori->id_kategori . ',id_kategori',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $kategori->update($request->all());

        LogAktivitas::catat('Mengupdate kategori', "Kategori: {$kategori->nama_kategori}");

        return redirect()->route('kategoris.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Hapus kategori
     */
    public function destroy(Kategori $kategori)
    {
        if ($kategori->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        LogAktivitas::catat('Menghapus kategori', "Kategori: {$nama}");

        return redirect()->route('kategoris.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}