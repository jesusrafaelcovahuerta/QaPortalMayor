<?php

namespace App\Http\Controllers\api;

use App\User;
use App\Member;
use App\CatchData;
use App\Section;
use App\Category;
use App\Content;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller\api\RegionController;

class UserController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::from('users as c')
                        ->selectRaw('rols.rol as rol, c.rut as rut, c.name as name, c.lastname as lastname, alliances.name as alliance, c.email as email, members.status as status, members.rol_id as rol_id')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->leftJoin('rols', 'rols.rol_id', '=', 'members.rol_id')
                        ->leftJoin('alliances', 'alliances.rut', '=', 'members.alliance_id')
                        ->where('rols.rol_id', '<>', 3)
                        ->orderBy('c.rut', 'ASC')
                        ->paginate(10);
        
        return $this->successResponse($users);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rol(Request $request)
    {
        $users = User::from('users as c')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->leftJoin('rols', 'rols.rol_id', '=', 'members.rol_id')
                        ->leftJoin('alliances', 'alliances.rut', '=', 'members.alliance_id')
                        ->leftJoin('rol_permissions', 'rol_permissions.rol_id', '=', 'rols.rol_id')
                        ->where('c.api_token', $request->api_token)
                        ->get();
        
        return $this->successResponse($users);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rut(Request $request)
    {
        $users = User::from('users as c')
                        ->selectRaw('c.rut as rut')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->leftJoin('alliances', 'alliances.rut', '=', 'members.alliance_id')
                        ->where('c.api_token', $request->api_token)
                        ->first();
        
        return $this->successResponse($users);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function catch(Request $request)
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
            $ip = $_SERVER['HTTP_CLIENT_IP'];  
            $ip = explode(":", $ip);
            $ip = $ip[3];
        } 
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
            $ip = explode(":", $ip);
            $ip = $ip[3];
        }
        else{  
            $ip = $_SERVER['REMOTE_ADDR'];  
        }

        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

        if($request->page == 'Home') {
            $request->page = 'Home';
        } else {
            $path = explode('_', $request->page);

            if($path[0] == 'section') {
                $section = Section::find($path[2]);
                $request->page = $section->section_title;
            } else if($path[0] == 'category') {
                $category = Category::find($path[2]);
                $request->page = $category->name;
            } else if($path[0] == 'content') {
                $content = Content::find($path[2]);
                $request->page = $content->title;
            }
        }

        $catch = new CatchData();
        $catch->ip = $ip;
        $catch->city = $details->city;
        $catch->region = $details->region;
        $catch->zip = $details->postal;
        $catch->coordinates = $details->loc;
        $catch->page = $request->page;
        $catch->save();
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function location(Request $request)
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
            $ip = $_SERVER['HTTP_CLIENT_IP'];  
            $ip = explode(":", $ip);
            $ip = $ip[3];
        } 
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
            $ip = explode(":", $ip);
            $ip = $ip[3];
        }
        else{  
            $ip = $_SERVER['REMOTE_ADDR'];  
        }

        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

        $details->city;

        $details->region;

        return $this->successResponse($catch);
    }


    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->rut = $request->rut;
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = md5($request->password);
        $user->api_token = md5($request->email.$user->phone);
        $user->question_id = $request->question_id;
        $user->answer = $request->answer;
        if($user->save()) {
            $member = new Member();
            $member->user_id = $request->rut;
            $member->alliance_id = $request->alliance_id;
            $member->rol_id = $request->rol_id;
            $member->status = 1;
            if($member->save()) {
                return $this->successResponse($member);
            } else {
                return $this->successResponse($member);
            }
        } else {
            return $this->errorResponse($user);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::from('users as c')
                        ->selectRaw('c.question_id as question_id, c.answer as answer, c.rut as rut, c.name as name, c.lastname as lastname, members.alliance_id as alliance_id, c.email as email, members.status as status, c.phone as phone')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->leftJoin('alliances', 'alliances.rut', '=', 'members.alliance_id')
                        ->where('c.rut', $id)
                        ->first();

        return $this->successResponse($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Member::where('user_id', $id)->first();
        $user->status = 0;
        if($user->save()) {
            return $this->successResponse($user);
        } else {
            return $this->errorResponse($user);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $old_rut = $user->rut;
        $user->rut = $request->rut;
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->question_id = $request->question_id;
        $user->answer = $request->answer;
        if($request->password != '') {
            $user->password = md5($request->password);
        }
        if($user->save()) {
            $member = Member::where('user_id', $old_rut)->first();
            $member->user_id = $request->rut;
            $member->alliance_id = $request->alliance_id;
            if($member->save()) {
                return $this->successResponse($member);
            } else {
                return $this->successResponse($member);
            }
        } else {
            return $this->errorResponse($user);
        }
    }

    /**
     * Activate the specified resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request)
    {
        $id = $request->segment(4);

        $user = Member::where('user_id', $id)->first();
        $user->status = 1;
        if($user->save()) {
            return $this->successResponse($user);
        } else {
            return $this->errorResponse($user);
        }
    }
}