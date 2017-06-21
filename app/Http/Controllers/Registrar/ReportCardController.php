<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class ReportCardController extends Controller
{
    function studentReport($idno,$sy){
        $name = "";
        $lrn = "";
        $infos = DB::Select("Select * from users u join student_infos s on u.idno = s.idno where u.idno = '$idno'");
        
        foreach($infos as $info){
            $name = $info->lastname.", ".$info->firstname." ".substr($info->middlename,0,1);
            $lrn = $info->lrn;
        }
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper([0, 0, 468, 612], 'portrait');
        $pdf->loadView("print.printcard",compact('idno','sy','name','lrn'));
        return $pdf->stream();
    }
}
