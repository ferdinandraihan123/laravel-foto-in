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

        if ($request->search) {
            $query->where('nama_jasa', 'like', '%' . $request->search . '%');
        }

        if ($request->kategori) {
            $query->where('id_kategori', $request->kategori);
        }

        if ($request->status == 'tersedia') {
            $query->where('status', 'aktif');
        }

        if ($request->status == 'tidak') {
            $query->where('status', 'nonaktif');
        }

        $products = $query->paginate(6)->withQueryString();

        $kategoris = Kategori::all();

        return view('kasir.produk.index', compact('products', 'kategoris'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        if ($product->status !== 'aktif') {
            abort(404);
        }

        return view('kasir.produk.show', compact('product'));
    }
}