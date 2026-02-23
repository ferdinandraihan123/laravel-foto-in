<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
    /**
     * Tampilkan semua log aktivitas
     */
    public function index(Request $request)
    {
        // Cek akses manual
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access. Halaman ini hanya untuk Admin dan Owner.');
        }

        $query = LogAktivitas::with('user');

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('id_user', $request->user_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Filter berdasarkan aktivitas (search)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('aktivitas', 'like', '%' . $request->search . '%')
                  ->orWhere('detail', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->dari_tanggal)->startOfDay(),
                Carbon::parse($request->sampai_tanggal)->endOfDay()
            ]);
        }

        // Urutkan dari terbaru
        $logs = $query->latest()->paginate(20);

        // Ambil daftar user untuk filter
        $users = User::whereIn('role', ['admin', 'kasir', 'owner'])
            ->orderBy('name')
            ->get();

        return view('log-aktivitas.index', compact('logs', 'users'));
    }

    /**
     * Tampilkan detail log aktivitas
     */
    public function show(LogAktivitas $logAktivitas)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access.');
        }

        $log = $logAktivitas->load('user');
        
        return view('log-aktivitas.show', compact('log'));
    }

    /**
     * Hapus log aktivitas
     */
    public function destroy(LogAktivitas $logAktivitas)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access. Hanya Admin yang bisa menghapus log.');
        }

        $logAktivitas->delete();

        return redirect()->route('log-aktivitas.index')
            ->with('success', 'Log aktivitas berhasil dihapus.');
    }

    /**
     * Hapus semua log aktivitas (clear logs)
     */
    public function clearAll()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        LogAktivitas::truncate();

        // Catat log ini
        LogAktivitas::catat('Menghapus semua log aktivitas', 'Semua log dihapus oleh admin');

        return redirect()->route('log-aktivitas.index')
            ->with('success', 'Semua log aktivitas berhasil dihapus.');
    }

    /**
     * Hapus log yang lebih dari 30 hari
     */
    public function cleanOldLogs()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $date = Carbon::now()->subDays(30);
        $deleted = LogAktivitas::where('created_at', '<', $date)->delete();

        LogAktivitas::catat('Membersihkan log lama', "{$deleted} log dihapus");

        return redirect()->route('log-aktivitas.index')
            ->with('success', "{$deleted} log lama berhasil dihapus.");
    }

    /**
     * Export log ke CSV
     */
    public function export(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access.');
        }

        $query = LogAktivitas::with('user');

        // Terapkan filter yang sama seperti di index
        if ($request->filled('user_id')) {
            $query->where('id_user', $request->user_id);
        }

        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->dari_tanggal)->startOfDay(),
                Carbon::parse($request->sampai_tanggal)->endOfDay()
            ]);
        }

        $logs = $query->latest()->get();

        // Nama file
        $filename = 'log-aktivitas-' . now()->format('Y-m-d-His') . '.csv';

        // Header CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // Buat callback untuk stream
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Header kolom
            fputcsv($file, ['No', 'Waktu', 'User', 'Role', 'Aktivitas', 'Detail', 'IP Address', 'User Agent']);
            
            // Data
            foreach ($logs as $index => $log) {
                fputcsv($file, [
                    $index + 1,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user->name ?? 'System',
                    $log->user->role ?? '-',
                    $log->aktivitas,
                    $log->detail ?? '-',
                    $log->ip_address ?? '-',
                    $log->user_agent ?? '-'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Log aktivitas untuk user tertentu
     */
    public function userLogs(User $user)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access.');
        }

        $logs = LogAktivitas::where('id_user', $user->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('log-aktivitas.user', compact('logs', 'user'));
    }
}