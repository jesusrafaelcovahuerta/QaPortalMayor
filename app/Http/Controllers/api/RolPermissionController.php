<?php

namespace App\Http\Controllers\api;

use App\Rol;
use App\RolPermission;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RolPermissionController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rols = Rol::from('rols as c')
                        ->selectRaw('c.*')
                        ->paginate(10);
        
        return $this->successResponse($rols);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rol = $request->input('rol');

        $rol = new Rol();
        $rol->rol = $request->input('rol');
        $rol->save();
    
        // Obtén los permisos seleccionados (array)
        $permissions = $request->input('permissions', []);

        $permissions = explode(',', $permissions);

        for ($i=0; $i < count($permissions); $i++) { 
            $rol_permission = new RolPermission();
            $rol_permission->rol_id = $rol->rol_id;
            $rol_permission->permission_id = $permissions[$i];
            $rol_permission->save();
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
        $rols_permissions = RolPermission::where('rol_id', $id)->get();

        return $this->successResponse($rols_permissions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rol = Rol::where('rol_id', $id)->first();

        if($rol->delete()) {
            $permissions = RolPermission::where('rol_id', $id)->get();

            foreach ($permissions as $permission) {
                $permission->delete();
            }

            return $this->successResponse($rol);
        } else {
            return $this->errorResponse($rol);
        }
    }
}