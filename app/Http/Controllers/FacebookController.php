<?php

namespace App\Http\Controllers;

use App\Mail\Collection;
use Illuminate\Http\Request;
use App\User;

class FacebookController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function botonfacebook()
    {
        return view('backend.index');
    }
}
