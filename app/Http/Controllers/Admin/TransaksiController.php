<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'product']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_unik', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transaksis = $query->latest()->paginate(15);

        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['user', 'product', 'product.kategori'])->findOrFail($id);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,selesai,batal'
        ]);

        $transaksi = Transaksi::with('product')->findOrFail($id);
        $oldStatus = $transaksi->status; // Simpan status lama untuk log
        $transaksi->update(['status' => $request->status]);

        LogAktivitas::catat(
            'Update status transaksi',
            "Nomor: {$transaksi->nomor_unik}, Status: {$oldStatus} → {$request->status}"
        );

        return back()->with('success', 'Status transaksi berhasil diupdate.');
    }
}
