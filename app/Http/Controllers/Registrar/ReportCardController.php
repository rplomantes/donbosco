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
        $class_no = "";
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
            $class_no = $status->class_no;
        }
        if($currSy == $sy){
            $getadviser = \App\CtrSection::where('schoolyear',$sy)->where('section',$section)->where('level',$level)->first();
        }else{
            $getadviser = DB::Select("select * from `ctr_sections_temp` where `schoolyear` = '$sy' and `section` = '$section' and `level` = '$level' limit 1");
        }
        
        
        if($getadviser){
            foreach($getadviser as $getadviser){
            $adviser = $getadviser->adviser;
            }
        }
        
        $grades = \App\Grade::where('idno',$idno)->where('isdisplaycard',1)->where('schoolyear',$sy)->orderBy('sortto','ASC')->get();
        $attendances = DB::Select("Select attendanceName,sum(Jun) as jun,sum(Jul) as jul,sum(Aug) as aug,sum(Sept) as sept,sum(Oct) as oct,sum(Nov) as nov,sum(Dece) as dece,sum(Jan) as jan,sum(Feb) as feb,sum(Mar) as mar,"
                . "sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct)+sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as total"
                . " from attendances where idno = '$idno' and schoolyear = '$sy' group by quarter,schoolyear,attendancetype order by sortto ASC");
        
        $ctr_attendances = DB::Select("Select sum(Jun) as jun,sum(Jul) as jul,sum(Aug) as aug,sum(Sept) as sept,sum(Oct) as oct,sum(Nov) as nov,sum(Dece) as dece,sum(Jan) as jan,sum(Feb) as feb,sum(Mar) as mar,"
                . "sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct)+sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as total"
                . " from ctr_attendances where level = '$level' and schoolyear = '$sy' group by schoolyear");
        
//        $pdf = \App::make('dompdf.wrapper');
//        
//        if(in_array($level,array("Grade 1","Grade 2","Grade 3","Grade 4","Grade 5","Grade 6"))){
//            $pdf->setPaper([0, 0, 468, 612], 'portrait');
//            $pdf->loadView("print.printelemcard",compact('idno','sy','name','lrn','adviser','section','level','grades','totalage','class_no','ctr_attendances','attendances'));            
//        }elseif(in_array($level,array("Grade 7","Grade 8","Grade 9","Grade 10"))){
//            $pdf->setPaper([0, 0, 468, 612], 'portrait');
//            //$pdf->loadView("print.printjhscard",compact('idno','sy','name','lrn','adviser','section','level','grades','totalage','class_no','ctr_attendances','attendances'));                        
//            
//        }elseif(in_array($level,array("Grade 11","Grade 12"))){
//            $pdf->setPaper([0, 0, 468, 612], 'portrait');
//            $pdf->loadView("print.printcard",compact('idno','sy','name','lrn','adviser','section','level','grades','totalage','class_no','ctr_attendances','attendances'));
//        }else{
//            
//        }
        return view("print.printjhscard",compact('idno','sy','name','lrn','adviser','section','level','grades','totalage','class_no','ctr_attendances','attendances'));

    }
}
