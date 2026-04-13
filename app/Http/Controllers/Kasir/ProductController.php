<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('kategori');

        // Filter search
        if ($request->search) {
            $query->where('nama_jasa', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->kategori) {
            $query->where('id_kategori', $request->kategori);
        }

        // HAPUS atau KOMENTAR baris ini!
        // $query->where('status', 'aktif');  // <-- JANGAN PAKAI INI

        // Tampilkan SEMUA produk (aktif DAN nonaktif)
        $products = $query->paginate(6)->withQueryString();

        $kategoris = Kategori::all();

        return view('kasir.produk.index', compact('products', 'kategoris'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        // Tetap bisa lihat detail meskipun nonaktif
        return view('kasir.produk.show', compact('product'));
    }
}
