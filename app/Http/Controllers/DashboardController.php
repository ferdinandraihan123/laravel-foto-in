<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirect ke dashboard sesuai role
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        } elseif ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }
        
        return redirect('/');
    }
}