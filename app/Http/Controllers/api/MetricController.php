<?php

namespace App\Http\Controllers\api;

use App\Audit;
use App\User;
use App\CatchData;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MetricController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->user = User::where('api_token', $request->api_token)->first();
    }

    public function index()
    {
        $catch_data = CatchData::all();

        return $this->errorResponse($catch_data);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_visit()
    {
        $catch_data = CatchData::from('catch_data as c')
                        ->selectRaw('c.page as page, COUNT(c.page) as total')
                        ->where('page', '<>', 'Home')
                        ->groupBy('c.page')
                        ->take(10)
                        ->orderBy('total', 'DESC')
                        ->get();

        $data[0][0] = "Pagina";
        $data[0][1] = "Valores";
        $data[0][2]['role'] = "style";

        $i = 1;

        $colors[1] = "#ccc";
        $colors[2] = "#1E6EFF";
        $colors[3] = "#FF771E";
        $colors[4] = "#FF1E1E";
        $colors[5] = "#3A9A9E";
        $colors[6] = "#863A9E";
        $colors[7] = "#B799C0";
        $colors[8] = "#308B55";
        $colors[9] = "#8B3034";
        $colors[10] = "#CBE337";

        foreach($catch_data as $catch_datum) {
            $data[$i][0] = $catch_datum->page;
            $data[$i][1] = $catch_datum->total;
            $data[$i][2] = $colors[$i];

            $i = $i + 1;
        }

        return $data;
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function city()
    {
        $catch_data = CatchData::from('catch_data as c')
                        ->selectRaw('c.city as city, COUNT(c.city) as total')
                        ->groupBy('c.city')
                        ->get();

        $data[0][0] = "Ciudad";
        $data[0][1] = "Valores";

        $i = 1;

        foreach($catch_data as $catch_datum) {
            $data[$i][0] = $catch_datum->city;
            $data[$i][1] = $catch_datum->total;

            $i = $i + 1;
        }

        return $data;
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function region()
    {
        $catch_data = CatchData::from('catch_data as c')
                        ->selectRaw('c.region as region, COUNT(c.region) as total')
                        ->groupBy('c.region')
                        ->get();

        $data[0][0] = "Región";
        $data[0][1] = "Valores";

        $i = 1;

        foreach($catch_data as $catch_datum) {
            $data[$i][0] = $catch_datum->region;
            $data[$i][1] = $catch_datum->total;

            $i = $i + 1;
        }

        return $data;
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function page()
    {
        $catch_data = CatchData::from('catch_data as c')
                        ->selectRaw('c.page as page, COUNT(c.page) as total')
                        ->selectRaw('c.page as page, COUNT(c.page) as total')
                        ->groupBy('c.page')
                        ->get();

        $data[0][0] = "Página";
        $data[0][1] = "Valores";

        $i = 1;

        foreach($catch_data as $catch_datum) {
            $data[$i][0] = $catch_datum->page;
            $data[$i][1] = $catch_datum->total;

            $i = $i + 1;
        }

        return $data;
    }
}