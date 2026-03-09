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
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
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

    public function show(LogAktivitas $logAktivitas)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access.');
        }

        $log = $logAktivitas->load('user');
        
        return view('owner.log-aktivitas.show', compact('log'));
    }

    public function userLogs(User $user)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access.');
        }

        $logs = LogAktivitas::where('id_user', $user->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('owner.log-aktivitas.user', compact('logs', 'user'));
    }

    public function export(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized access.');
        }

        $query = LogAktivitas::with('user');

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
}