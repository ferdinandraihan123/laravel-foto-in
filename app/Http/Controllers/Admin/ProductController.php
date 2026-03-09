<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('kategori');

        // SEARCH
        if ($request->filled('search')) {
            $query->where('nama_jasa', 'like', '%' . $request->search . '%');
        }

        // FILTER KATEGORI
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query
            ->orderBy('status', 'desc')
            ->orderBy('nama_jasa')
            ->paginate(10)
            ->withQueryString();

        $kategoris = Kategori::where('status', 'aktif')->get();

        return view('admin.produk.index', compact('products', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::where('status', 'aktif')->get();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
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

        $produk = Product::create($data);  // Ubah dari $product ke $produk

        LogAktivitas::catat('Menambah produk', "Produk: {$produk->nama_jasa}");

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $produk)  // Parameter menggunakan $produk (route binding)
    {
        // Load relasi kategori
        $produk->load('kategori');
        
        // Kirim ke view dengan nama 'product' (sesuai dengan yang digunakan di blade)
        return view('admin.produk.show', [
            'product' => $produk  // Penting: dikirim sebagai 'product'
        ]);
    }

    public function edit(Product $produk)  // PERBAIKAN: Ubah dari $product ke $produk
    {
        $kategoris = Kategori::where('status', 'aktif')->get();
        return view('admin.produk.edit', compact('produk', 'kategoris'));  // Ubah compact('product') ke compact('produk')
    }

    public function update(Request $request, Product $produk)  // PERBAIKAN: Ubah dari $product ke $produk
    {
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
            if ($produk->gambar) {  // Ubah dari $product ke $produk
                Storage::disk('public')->delete($produk->gambar);  // Ubah dari $product ke $produk
            }
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $produk->update($data);  // Ubah dari $product ke $produk

        LogAktivitas::catat('Mengupdate produk', "Produk: {$produk->nama_jasa}");  // Ubah dari $product ke $produk

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $produk)  // PERBAIKAN: Ubah dari $product ke $produk
    {
        if ($produk->transaksis()->count() > 0) {  // Ubah dari $product ke $produk
            return back()->with('error', 'Produk tidak bisa dihapus karena sudah memiliki transaksi');
        }

        if ($produk->gambar) {  // Ubah dari $product ke $produk
            Storage::disk('public')->delete($produk->gambar);  // Ubah dari $product ke $produk
        }

        $nama = $produk->nama_jasa;  // Ubah dari $product ke $produk
        $produk->delete();  // Ubah dari $product ke $produk

        LogAktivitas::catat('Menghapus produk', "Produk: {$nama}");

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}