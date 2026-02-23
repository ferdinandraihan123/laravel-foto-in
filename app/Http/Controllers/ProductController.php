<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Tampilkan list produk/bundling foto
     * Untuk admin: semua produk, untuk kasir: hanya produk aktif
     */
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $products = Product::with('kategori')
                ->orderBy('status', 'desc')
                ->orderBy('nama_jasa')
                ->paginate(10);
        } else {
            $products = Product::with('kategori')
                ->where('status', 'aktif')
                ->orderBy('nama_jasa')
                ->paginate(12);
        }
        
        return view('products.index', compact('products'));
    }

    /**
     * Form tambah produk (hanya admin)
     */
    public function create()
    {
        $this->authorize('admin');
        
        $kategoris = Kategori::where('status', 'aktif')->get();
        return view('products.create', compact('kategoris'));
    }

    /**
     * Simpan produk baru
     * Sesuai flowchart: Masukkan data nama paket dan harga
     */
    public function store(Request $request)
    {
        $this->authorize('admin');
        
        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'nama_jasa' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|numeric|min:0.5',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product = Product::create($data);

        LogAktivitas::catat('Menambah produk', "Produk: {$product->nama_jasa}");

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Detail produk
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Form edit produk
     */
    public function edit(Product $product)
    {
        $this->authorize('admin');
        
        $kategoris = Kategori::where('status', 'aktif')->get();
        return view('products.edit', compact('product', 'kategoris'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('admin');
        
        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'nama_jasa' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|numeric|min:0.5',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($data);

        LogAktivitas::catat('Mengupdate produk', "Produk: {$product->nama_jasa}");

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    /**
     * Hapus produk
     */
    public function destroy(Product $product)
    {
        $this->authorize('admin');
        
        if ($product->transaksis()->count() > 0) {
            return back()->with('error', 'Produk tidak bisa dihapus karena sudah memiliki transaksi');
        }

        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        $nama = $product->nama_jasa;
        $product->delete();

        LogAktivitas::catat('Menghapus produk', "Produk: {$nama}");

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    /**
     * Authorize admin
     */
    private function authorize($role)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
    }
}   