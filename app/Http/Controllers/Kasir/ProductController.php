<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('kategori')
            ->where('status', 'aktif')
            ->orderBy('nama_jasa')
            ->paginate(12);
        
        return view('kasir.produk.index', compact('products'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'aktif') {
            abort(404);
        }
        
        return view('kasir.produk.show', compact('product'));
    }
}