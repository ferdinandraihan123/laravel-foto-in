<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of all transactions.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['kasir', 'product'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kasir
        if ($request->filled('kasir_id')) {
            $query->where('kasir_id', $request->kasir_id);
        }

        $transaksis = $query->paginate(15);
        $kasirs = User::where('role', 'kasir')->get();
        
        return view('owner.transaksi.index', compact('transaksis', 'kasirs'));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create(Request $request)
    {
        $products = Product::where('status', 'aktif')->get();
        $kasirs = User::where('role', 'kasir')->get();
        
        // Jika ada id_jasa dari parameter URL
        $selectedProduct = null;
        if ($request->has('id_jasa')) {
            $selectedProduct = Product::find($request->id_jasa);
        }
        
        return view('owner.transaksi.create', compact('products', 'kasirs', 'selectedProduct'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jasa' => 'required|exists:products,id_jasa',
            'kasir_id' => 'required|exists:users,id',
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:tunai,debit,kredit,qris',
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'id_jasa' => $request->id_jasa,
                'kasir_id' => $request->kasir_id,
                'jumlah' => $request->jumlah,
                'total_harga' => $request->total_harga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => 'selesai',
                'catatan' => $request->catatan,
                'tanggal_transaksi' => now(),
            ]);

            DB::commit();
            
            return redirect()->route('owner.transaksi.show', $transaksi->id)
                ->with('success', 'Transaksi berhasil dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['kasir', 'product'])
            ->findOrFail($id);
            
        return view('owner.transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $products = Product::all();
        $kasirs = User::where('role', 'kasir')->get();
        
        return view('owner.transaksi.edit', compact('transaksi', 'products', 'kasirs'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $request->validate([
            'id_jasa' => 'required|exists:products,id_jasa',
            'kasir_id' => 'required|exists:users,id',
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:tunai,debit,kredit,qris',
            'status' => 'required|in:pending,proses,selesai,batal',
            'catatan' => 'nullable|string|max:500',
        ]);

        $transaksi->update($request->all());
        
        return redirect()->route('owner.transaksi.show', $id)
            ->with('success', 'Transaksi berhasil diupdate');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        // Cek apakah transaksi bisa dihapus
        if ($transaksi->status === 'selesai') {
            return back()->with('error', 'Transaksi selesai tidak dapat dihapus');
        }
        
        $transaksi->delete();
        
        return redirect()->route('owner.transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * Export transactions report.
     */
    public function export(Request $request)
    {
        $query = Transaksi::with(['kasir', 'product']);
        
        // Filter berdasarkan tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        
        $transaksis = $query->orderBy('created_at', 'desc')->get();
        
        // Generate PDF atau Excel
        // Bisa menggunakan library seperti maatwebsite/excel atau dompdf
        
        return view('owner.transaksi.export', compact('transaksis'));
    }

    /**
     * Cancel transaction.
     */
    public function batal($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        if ($transaksi->status === 'selesai') {
            return back()->with('error', 'Transaksi selesai tidak dapat dibatalkan');
        }
        
        $transaksi->update(['status' => 'batal']);
        
        return redirect()->route('owner.transaksi.index')
            ->with('success', 'Transaksi berhasil dibatalkan');
    }

    /**
     * Print receipt.
     */
    public function struk($id)
    {
        $transaksi = Transaksi::with(['kasir', 'product'])->findOrFail($id);
        
        return view('owner.transaksi.struk', compact('transaksi'));
    }
}