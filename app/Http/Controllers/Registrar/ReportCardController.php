<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class ReportCardController extends Controller
{
    static function studentReport($idno,$sy,$sem = null){
        $name = "";
        $lrn = "";
        $section = "";
        $level = "";
        $adviser = "";
        $class_no = "";
        $gender = "";
        $infos = DB::Select("Select * from users u join student_infos s on u.idno = s.idno where u.idno = '$idno'");
        $currSy = \App\CtrSchoolYear::first()->schoolyear;
        $birthdate = "0000-00-00";
        $attendance = "";
        
        
        foreach($infos as $info){
            $name = $info->lastname.", ".$info->firstname." ".substr($info->middlename,0,1).".";
            $lrn = $info->lrn;
            $birthdate = $info->birthDate;
            $gender = $info->gender;
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
        
        if($level == 'Kindergarten'){
            
        }elseif($level == 'Grade 1' || $level == 'Grade 2' || $level == 'Grade 3' || $level == 'Grade 4' || $level == 'Grade 5' || $level == 'Grade 6'){
            return self::printelem($idno,$sy,$name,$lrn,$adviser,$section,$level,$class_no,$totalage);
        }elseif($level == 'Grade 7' || $level == 'Grade 8'){
            return self::printjhs1($idno,$sy,$name,$lrn,$adviser,$section,$level,$class_no,$totalage);
        }elseif($level == 'Grade 9' || $level == 'Grade 10'){
            return self::printjhs2($idno,$sy,$name,$lrn,$adviser,$section,$level,$class_no,$totalage,$status);
        }elseif($level == 'Grade 11' || $level == 'Grade 12'){
            return $this->printshs($idno,$sy,$name,$lrn,$gender,$adviser,$section,$level,$class_no,$totalage,$sem,$infos,$status);
        }else{
            
        }
    }
    
    static function printjhs1($idno,$sy,$name,$lrn,$adviser,$section,$level,$class_no,$totalage){
        $grades = \App\Grade::where('idno',$idno)->where('isdisplaycard',1)->where('schoolyear',$sy)->orderBy('sortto','ASC')->get();
        $attendances = DB::Select("Select attendanceName,sum(Jun) as jun,sum(Jul) as jul,sum(Aug) as aug,sum(Sept) as sept,sum(Oct) as oct,sum(Nov) as nov,sum(Dece) as dece,sum(Jan) as jan,sum(Feb) as feb,sum(Mar) as mar,"
                . "sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct)+sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as total"
                . " from attendances where idno = '$idno' and schoolyear = '$sy' group by quarter,schoolyear,attendancetype order by sortto ASC");
        
        $ctr_attendances = DB::Select("Select sum(Jun) as jun,sum(Jul) as jul,sum(Aug) as aug,sum(Sept) as sept,sum(Oct) as oct,sum(Nov) as nov,sum(Dece) as dece,sum(Jan) as jan,sum(Feb) as feb,sum(Mar) as mar,"
                . "sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct)+sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as total"
                . " from ctr_attendances where level = '$level' and schoolyear = '$sy' group by schoolyear");

        return view("print.printjhscard",compact('idno','sy','name','lrn','adviser','section','level','grades','totalage','class_no','ctr_attendances','attendances'));
    }
    
    static function printjhs2($idno,$sy,$name,$lrn,$adviser,$section,$level,$class_no,$totalage,$status){
        $grades = \App\Grade::where('idno',$idno)->where('isdisplaycard',1)->where('schoolyear',$sy)->orderBy('sortto','ASC')->get();
        $attendances = DB::Select("Select attendanceName,sum(Jun) as jun,sum(Jul) as jul,sum(Aug) as aug,sum(Sept) as sept,sum(Oct) as oct,sum(Nov) as nov,sum(Dece) as dece,sum(Jan) as jan,sum(Feb) as feb,sum(Mar) as mar,"
                . "sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct)+sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as total"
                . " from attendances where idno = '$idno' and schoolyear = '$sy' group by quarter,schoolyear,attendancetype order by sortto ASC");
        
        $ctr_attendances = DB::Select("Select sum(Jun) as jun,sum(Jul) as jul,sum(Aug) as aug,sum(Sept) as sept,sum(Oct) as oct,sum(Nov) as nov,sum(Dece) as dece,sum(Jan) as jan,sum(Feb) as feb,sum(Mar) as mar,"
                . "sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct)+sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as total"
                . " from ctr_attendances where level = '$level' and schoolyear = '$sy' group by schoolyear");

        return view("print.printjhscard2",compact('idno','sy','name','lrn','adviser','section','level','grades','totalage','class_no','ctr_attendances','attendances','status'))->render();
    }
    
    static function printshs($idno,$sy,$name,$lrn,$gender,$adviser,$section,$level,$class_no,$totalage,$sem,$infos,$status){
        $grades = \App\Grade::where('idno',$idno)->where('isdisplaycard',1)->where('schoolyear',$sy)->whereIn('semester',[$sem,0])->orderBy('sortto','ASC')->get();
        $attendances = DB::Select("Select attendanceName,sum(Jun) as jun,sum(Jul) as jul,sum(Aug) as aug,sum(Sept) as sept,sum(Oct) as oct,sum(Nov) as nov,sum(Dece) as dece,sum(Jan) as jan,sum(Feb) as feb,sum(Mar) as mar,"
                . "sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct)+sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as total,sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct) as sem1,sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as sem2"
                . " from attendances where idno = '$idno' and schoolyear = '$sy' group by quarter,schoolyear,attendancetype order by sortto ASC");
        
        $ctr_attendances = DB::Select("Select sum(Jun) as jun,sum(Jul) as jul,sum(Aug) as aug,sum(Sept) as sept,sum(Oct) as oct,sum(Nov) as nov,sum(Dece) as dece,sum(Jan) as jan,sum(Feb) as feb,sum(Mar) as mar,"
                . "sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct)+sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as total,sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct) as sem1,sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as sem2"
                . " from ctr_attendances where level = '$level' and schoolyear = '$sy' group by schoolyear");

        return view("print.printshscard",compact('idno','sy','name','lrn','gender','adviser','section','level','grades','totalage','class_no','ctr_attendances','attendances','sem','infos','status'));
    }
    
    static function printelem($idno,$sy,$name,$lrn,$adviser,$section,$level,$class_no,$totalage){
        $grades = \App\Grade::where('idno',$idno)->where('isdisplaycard',1)->where('schoolyear',$sy)->orderBy('sortto','ASC')->get();
        $attendances = DB::Select("Select attendanceName,sum(Jun) as jun,sum(Jul) as jul,sum(Aug) as aug,sum(Sept) as sept,sum(Oct) as oct,sum(Nov) as nov,sum(Dece) as dece,sum(Jan) as jan,sum(Feb) as feb,sum(Mar) as mar,"
                . "sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct)+sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as total"
                . " from attendances where idno = '$idno' and schoolyear = '$sy' group by quarter,schoolyear,attendancetype order by sortto ASC");
        
        $ctr_attendances = DB::Select("Select sum(Jun) as jun,sum(Jul) as jul,sum(Aug) as aug,sum(Sept) as sept,sum(Oct) as oct,sum(Nov) as nov,sum(Dece) as dece,sum(Jan) as jan,sum(Feb) as feb,sum(Mar) as mar,"
                . "sum(Jun)+sum(Jul)+sum(Aug)+sum(Sept)+sum(Oct)+sum(Nov)+sum(Dece)+sum(Jan)+sum(Feb)+sum(Mar) as total"
                . " from ctr_attendances where level = '$level' and schoolyear = '$sy' group by schoolyear");

        return view("print.printelem",compact('idno','sy','name','lrn','adviser','section','level','grades','totalage','class_no','ctr_attendances','attendances'));
    }
}
