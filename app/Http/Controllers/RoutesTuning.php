<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class RoutesTuning extends Controller
{
    public function registerTuningRoutes()
    {
        $tunings = \App\route::all();


        // Now loop all tunings and declare routes
        foreach($tunings as $tuning) {
            $url = $tuning->url;
            $route_function = $tuning->function;
            $route_controller = $tuning->controller;
            Route::any($url, $route_controller.'@'.$route_function); // You may use get/post
        }
    }

    public function TuningMethod($tuning = null)
    {
        // $tuning will contain the current tuning name, check
        dd($tuning);
    }

}
