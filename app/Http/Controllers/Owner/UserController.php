<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request){
                $q->where('name','like','%'.$request->search.'%')
                  ->orWhere('email','like','%'.$request->search.'%');
            });
        }

        if ($request->role) {
            $query->where('role',$request->role);
        }

        if ($request->status) {
            $query->where('status',$request->status);
        }

        $users = $query->paginate(10)->withQueryString();

        return view('owner.users.index', compact('users'));
    }
}