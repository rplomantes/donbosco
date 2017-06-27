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
        $section = "";
        $level = "";
        $adviser = "";
        $infos = DB::Select("Select * from users u join student_infos s on u.idno = s.idno where u.idno = '$idno'");
        $currSy = \App\CtrSchoolYear::first()->schoolyear;
        $birthdate = "0000-00-00";
        $attendance = "";

        foreach($infos as $info){
            $name = $info->lastname.", ".$info->firstname." ".substr($info->middlename,0,1);
            $lrn = $info->lrn;
            $birthdate = $info->birthDate;
        }
        
        $age_year = date_diff(date_create($birthdate), date_create('today'))->y;
        $age_month = date_diff(date_create($birthdate), date_create('today'))->m;
        $age= $age_year.".".$age_month;
        $totalage = $age;
        
        if($currSy == $sy){
            $status = \App\Status::where('idno',$idno)->where('schoolyear',$sy)->orderBy('id','DESC')->first();
        }else{
            $status = \App\StatusHistory::where('idno',$idno)->where('schoolyear',$sy)->orderBy('id','DESC')->first();
        }
        
        if($status){
            $section = $status->section;
            $level = $status->level;
        }
        
        $getadviser = \App\CtrSection::where('schoolyear',$sy)->where('section',$section)->where('level',$level)->first();
        
        if($getadviser){
            $adviser = $getadviser->adviser;
        }
        
        $grades = \App\Grade::where('idno',$idno)->where('isdisplaycard',1)->where('schoolyear',$sy)->orderBy('sortto','ASC')->get();
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper([0, 0, 468, 612], 'portrait');
        $pdf->loadView("print.printcard",compact('idno','sy','name','lrn','adviser','section','level','grades','totalage'));
        return $pdf->stream();
    }
}
