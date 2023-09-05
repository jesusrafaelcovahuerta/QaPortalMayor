<?php

namespace App\Http\Controllers\api;

use App\Commune;
use App\User;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommuneController extends ApiResponseController
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
    public function index(Request $request)
    {
        $region_id = $request->segment(3);

        $communes = Commune::where('region_id', $region_id)->orderBy('commune', 'ASC')->get();
        
        return $this->successResponse($communes);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find()
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

        $apiKey = "D1ADF7A7E66DB897DEF099B7CD05DE34";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ip2location.io/?key={$apiKey}&ip={$ip}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $details = json_decode($response);
        }

        $city_name = $details->city_name;

        $commune = Commune::where('commune', 'like', '%' . $city_name . '%')->first();
        
        return $this->successResponse($commune);
    }
}