<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Kategori;

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

        $products = $query->latest()->paginate(10);

        $kategoris = Kategori::all();

        return view('owner.produk.index', compact('products', 'kategoris'));
    }
}
