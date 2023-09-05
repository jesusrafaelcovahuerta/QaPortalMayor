<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\RolPermission;
use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponseController;
use Session;

class UserController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::from('users as c')
                        ->selectRaw('c.*, members.rol_id as rol_id')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->where('api_token', $request->api_token)
                        ->first();

        return $this->successResponse($user);
    }


    /**
     * Retrieve the user data to log into the site.
     *
     * @return \Illuminate\Http\Response
     */
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
            return redirect('login');
        }
    }

    /**
     * Close the session to the use.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        if (Auth::logout()) {
            return redirect('login');
        } else {
            return redirect('account');
        }
    }

}
