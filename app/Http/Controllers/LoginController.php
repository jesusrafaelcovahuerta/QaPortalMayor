<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $rut = $request->rut;
        $password = md5($request->password);

        $user_qty = User::where('rut', $rut)->where('password', $password)->count();

        if ($user_qty > 0) {
            $user = User::from('users as c')
                        ->selectRaw('c.*, members.rol_id as rol_id')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->where('rut', $rut)->where('password', $password)
                        ->first();

            if (! Auth::loginUsingId($user->rut, true)) {
                return redirect('login');
            } else {
                if($user->rol_id == 1) {
                    return redirect('account');
                } else {
                    return redirect('content');
                }
            }
        } else {
            return redirect('login/error');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
