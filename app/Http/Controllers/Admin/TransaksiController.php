<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * JADWAL BOOKING (berdasarkan tanggal_booking)
     * Urutan: tanggal_booking terbaru di atas, jam tercepat, lalu created_at terbaru
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'product'])
            ->orderBy('tanggal_booking', 'desc')
            ->orderBy('jam_booking', 'asc')
            ->orderBy('created_at', 'desc');

        // Filter search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_unik', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $request->search . '%');
            });
        }

        // Filter tanggal booking
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_booking', $request->tanggal);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transaksis = $query->paginate(10);

        return view('admin.transaksi.index', compact('transaksis'));
    }

    /**
     * DETAIL TRANSAKSI
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['user', 'product', 'product.kategori'])->findOrFail($id);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    /**
     * UPDATE STATUS TRANSAKSI
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,selesai,batal'
        ]);

        $transaksi = Transaksi::with('product')->findOrFail($id);
        $oldStatus = $transaksi->status;
        $transaksi->update(['status' => $request->status]);

        LogAktivitas::catat(
            'Update status transaksi',
            "Nomor: {$transaksi->nomor_unik}, Status: {$oldStatus} → {$request->status}"
        );

        return back()->with('success', 'Status berhasil diupdate.');
    }
}