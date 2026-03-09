<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('kategori')
            ->withCount('transaksis')
            ->orderBy('nama_jasa')
            ->paginate(15);
        
        return view('owner.produk.index', compact('products'));
    }
}