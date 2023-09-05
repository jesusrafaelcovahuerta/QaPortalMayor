<?php

namespace App\Http\Controllers\api;

use App\Audit;
use App\User;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->user = User::where('api_token', $request->api_token)->first();
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $audits = Audit::from('audits as c')
                        ->selectRaw('c.audit_id as audit_id, CONCAT(users.name, " ", users.lastname) as member, c.page as page, c.created_at as date')
                        ->leftJoin('users', 'users.rut', '=', 'c.member_id')
                        ->orderBy('c.created_at', 'DESC')
                        ->paginate(10);
        
        return $this->successResponse($audits);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $audit = new Audit();
        $audit->member_id = $this->user->rut;
        $audit->page = $request->page;
        $audit->save();
    }
}