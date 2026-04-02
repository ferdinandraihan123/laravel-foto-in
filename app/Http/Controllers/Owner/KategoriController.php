<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::withCount('products');

        if ($request->search) {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $kategoris = $query->latest()->paginate(10);

        return view('owner.kategori.index', compact('kategoris'));
    }
}