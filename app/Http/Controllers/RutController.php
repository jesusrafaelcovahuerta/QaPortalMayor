<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RutController extends Controller
{
    public function index()
    {
        return view('auth.rut');
    }

    public function verify(Request $request)
    {
        $user_qty = User::from('users as c')
                        ->selectRaw('c.*, members.rol_id as rol_id')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->where('rut', $request->rut)
                        ->count();

        if($user_qty > 0) {
            return redirect('question');
        } else {
            return redirect('rut/error');
        }
    }
}
