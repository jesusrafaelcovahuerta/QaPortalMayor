<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationController extends ApiResponseController
{
    public function index(Request $request)
    {
        $page = $request->page;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://scraping.conectamayor.cl/api/database?page='.$page.'&api_token=25f9e794323b453885f5181f1b624d0b'); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        $data = curl_exec($ch); 
        curl_close($ch); 
        print_r($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://scraping.conectamayor.cl/api/database/'.$id.'/edit'); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        $data = curl_exec($ch); 
        curl_close($ch); 
        print_r($data);
    }
}