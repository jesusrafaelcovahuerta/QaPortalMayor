<?php

namespace App\Http\Controllers\api;

use App\Permission;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PermissionController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = Permission::orderBy('permission', 'asc')->get();
        
        return $this->successResponse($permissions);
    }
}