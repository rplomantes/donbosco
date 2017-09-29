<?php

namespace App\Http\Controllers\Economer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
class Charts extends Controller
{
    static function piechart($width,$height,$labels,$colors,$data){
        $chartjs = app()->chartjs
                ->name('pieChartTest')
                ->type('pie')
                ->size(['width' => $width, 'height' => $height])
                ->labels($labels)
                ->datasets([
                    [
                        'backgroundColor' => $colors,
                        'hoverBackgroundColor' => $colors,
                        'data' => $data
                    ]
                ])
                ->options([]);

        return $chartjs;


    }
}
