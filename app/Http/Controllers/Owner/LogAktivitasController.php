<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $userRole = auth()->user()->role;
        if ($userRole != 'admin' && $userRole != 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $query = LogAktivitas::with('user');

        if ($request->filled('user_id')) {
            $query->where('id_user', $request->user_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('aktivitas', 'like', '%' . $request->search . '%')
                  ->orWhere('detail', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->dari_tanggal)->startOfDay(),
                Carbon::parse($request->sampai_tanggal)->endOfDay()
            ]);
        }

        $logs = $query->latest()->paginate(20);
        $users = User::whereIn('role', ['admin', 'kasir', 'owner'])
            ->orderBy('name')
            ->get();

        return view('owner.log-aktivitas.index', compact('logs', 'users'));
    }

    public function show($id)
    {
        $userRole = auth()->user()->role;
        if ($userRole != 'admin' && $userRole != 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $log = LogAktivitas::with('user')->findOrFail($id);
        
        return view('owner.log-aktivitas.show', compact('log'));
    }

    public function userLogs($id)
    {
        $userRole = auth()->user()->role;
        if ($userRole != 'admin' && $userRole != 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($id);
        $logs = LogAktivitas::where('id_user', $user->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('owner.log-aktivitas.user', compact('logs', 'user'));
    }

    public function export(Request $request)
    {
        $userRole = auth()->user()->role;
        if ($userRole != 'admin' && $userRole != 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $query = LogAktivitas::with('user');

        if ($request->filled('user_id')) {
            $query->where('id_user', $request->user_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('aktivitas', 'like', '%' . $request->search . '%')
                  ->orWhere('detail', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->dari_tanggal)->startOfDay(),
                Carbon::parse($request->sampai_tanggal)->endOfDay()
            ]);
        }

        $logs = $query->latest()->get();

        $filename = 'log-aktivitas-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Waktu', 'User', 'Role', 'Aktivitas', 'Detail', 'IP Address', 'User Agent']);
            
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

    public function clean()
    {
        $userRole = auth()->user()->role;
        if ($userRole != 'admin' && $userRole != 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $deleted = LogAktivitas::where('created_at', '<', now()->subDays(30))->delete();
        
        LogAktivitas::create([
            'id_user' => auth()->id(),
            'aktivitas' => 'Membersihkan log lama',
            'detail' => 'Menghapus ' . $deleted . ' log yang lebih dari 30 hari',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        return redirect()->back()->with('success', 'Berhasil menghapus ' . $deleted . ' log yang lebih dari 30 hari');
    }

    public function clearAll()
    {
        $userRole = auth()->user()->role;
        if ($userRole != 'admin' && $userRole != 'owner') {
            abort(403, 'Unauthorized access.');
        }

        LogAktivitas::truncate();
        
        LogAktivitas::create([
            'id_user' => auth()->id(),
            'aktivitas' => 'Menghapus semua log',
            'detail' => 'Semua log aktivitas dihapus',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        return redirect()->back()->with('success', 'Semua log aktivitas berhasil dihapus');
    }
}