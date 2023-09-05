<?php

namespace App\Http\Controllers\api;

use App\Alliance;
use App\User;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AllianceController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->user = User::where('api_token', $request->api_token)->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rut = $request->segment(4);
        $name = $request->segment(5);

        if(($rut == 'null' && $name == 'null')
        || ($rut == '' && $name == '')
        ) {
            $alliances = Alliance::from('alliances as c')
                        ->selectRaw('c.rut as rut, c.name as name, c.alias as alias, c.status as status, c.contact as contact, c.contact_email as contact_email, c.contact_phone as contact_phone, c.start_date as start_date, c.end_date as end_date')
                        ->orderBy('c.created_at', 'DESC')
                        ->paginate(10);
        } else {
            $query = "";

            if ($rut != 'null') {
                $query .= 'c.rut LIKE "%'.$rut.'%"';
            }

            if ($name != 'null') {
                if ($rut != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'c.name LIKE "%'.$name.'%"';
            }

            $alliances = Alliance::from('alliances as c')
                        ->selectRaw('c.rut as rut, c.name as name, c.alias as alias, c.status as status, c.contact as contact, c.contact_email as contact_email, c.contact_phone as contact_phone, c.start_date as start_date, c.end_date as end_date')
                        ->whereRaw($query)
                        ->orderBy('c.created_at', 'DESC')
                        ->paginate(10);
        }
        
        return $this->successResponse($alliances);
    }

    /**
     * A listing of the resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $alliances = Alliance::where('status', 1)
                        ->orderBy('rut', 'ASC')
                        ->get();
        
        return $this->successResponse($alliances);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $alliance = new Alliance();
        $alliance->rut = $request->rut;
        $alliance->name = $request->name;
        $alliance->alias = $request->alias;
        $alliance->contact = $request->contact;
        $alliance->contact_email = $request->email;
        $alliance->contact_phone = $request->phone;
        $alliance->start_date = $request->start_date;
        $alliance->end_date = $request->end_date;
        $alliance->status = 1;
        if($alliance->save()) {
            return $this->successResponse($alliance);
        } else {
            return $this->errorResponse($alliance);
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
        $alliance = Alliance::find($id);

        return $this->successResponse($alliance);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $alliance = Alliance::find($id);
        $alliance->status = 0;
        if($alliance->save()) {
            return $this->successResponse($alliance);
        } else {
            return $this->errorResponse($alliance);
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
        $alliance = Alliance::find($id);
        $alliance->rut = $request->rut;
        $alliance->name = $request->name;
        $alliance->alias = $request->alias;
        $alliance->contact = $request->contact;
        $alliance->contact_email = $request->email;
        $alliance->contact_phone = $request->phone;
        $alliance->start_date = $request->start_date;
        $alliance->end_date = $request->end_date;
        $alliance->status = 1;
        if($alliance->save()) {
            return $this->successResponse($alliance);
        } else {
            return $this->errorResponse($alliance);
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

        $alliance = Alliance::find($id);
        $alliance->status = 1;
        if($alliance->save()) {
            return $this->successResponse($alliance);
        } else {
            return $this->errorResponse($alliance);
        }
    }
}