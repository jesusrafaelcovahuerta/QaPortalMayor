<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller
{
    public function index()
    {
        return view('auth.question');
    }

    public function verify(Request $request)
    {
        $user_qty = User::from('users as c')
                        ->selectRaw('c.*')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->where('question_id', $request->question_id)
                        ->where('answer', $request->answer)
                        ->count();
        
        $user = User::from('users as c')
                        ->selectRaw('c.*')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->where('question_id', $request->question_id)
                        ->where('answer', $request->answer)
                        ->first();

        if($user_qty > 0) {
            return redirect('password/'.$user->password);
        } else {
            return redirect('question/error');
        }
    }
}
