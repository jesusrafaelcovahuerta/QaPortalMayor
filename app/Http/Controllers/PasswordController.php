<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    public function index()
    {
        return view('auth.password');
    }

    public function update(Request $request)
    {
        $user_qty = User::from('users as c')
                        ->selectRaw('c.*')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->where('password', $request->hidden_password)
                        ->count();
        
        if($user_qty > 0) {
            $user = User::from('users as c')
                        ->selectRaw('c.*')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->where('password', $request->hidden_password)
                        ->first();

            $user->password = md5($request->password);
            $user->save();

            return redirect('login/success');
        } else {
            return redirect('password/error');
        }
    }
}
