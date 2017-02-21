<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Excel;
use DB;
class AttendanceController extends Controller
{

    function index(){
        return view('vincent.test');
    }
    
    function importMonthlyAttendance(){
        if(Input::hasFile('import_file2')){
            $schoolyear = \App\ctrSchoolYear::first();
            $sy = $schoolyear->schoolyear;
            $path = Input::file('import_file2')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();

            foreach($data as $book){
                $sheet=$book->getTitle();
                $sheets = Excel::selectSheets($book->getTitle())->load($path,null)->get();

                foreach($sheets as $key=>$value){
                    
                    $idnof = $value->idno;
                    
                    if(strlen($idnof)==5){
                        $idnof = "0".$idnof;
                    }elseif(strlen($idnof)==4){
                        $idnof = "00".$idnof;
                    }
                    
                    $this->updateMonthlyAttd($idnof,3,'DAYT',$value->dayt,$sheet,$sy);
                    $this->updateMonthlyAttd($idnof,1,'DAYP',$value->dayp,$sheet,$sy);
                    $this->updateMonthlyAttd($idnof,2,'DAYA',$value->daya,$sheet,$sy);
                    
                    $quarter = \App\CtrQuarter::first();
        
                    $insert[] = ['idno'=>$idnof, 'qtrperiod'=>$quarter->qtrperiod,'schoolyear'=>$sy,'month'=>$sheet,
                        'DAYA'=>$value->daya, 'DAYP'=>$value->dayp,'DAYT'=>$value->dayt];                    
                } 

            }
            if(!empty($insert)){
                        DB::table('attendance_repos')->insert($insert);
                    }            
        }
        return redirect(url('/importGrade'));
        
    }
    
    function updateMonthlyAttd($idno,$sort,$type,$value,$month,$sy){
        $check  = \App\Attendance::where('idno',$idno)->where('attendancetype',$type)->where('schoolyear',$sy)->get();
        $months=ucfirst(strtolower($month));

        
        if($months == "Sep"){
            $months = "Sept";
        }
        if($months == "Dec"){
            $months = "Dece";
        }        
        
        if($check->isEmpty()){
            $attendace  = new \App\Attendance;
            $attendace->attendancetype=$type;
            $attendace->sortto=$sort;
            $attendace->schoolyear=$sy;
        }else{
            $attendace  = \App\Attendance::where('idno',$idno)->where('attendancetype',$type)->where('schoolyear',$sy)->first();
        }
        $quarter = \App\CtrQuarter::first();
        
        if((!$check->isEmpty()) && $quarter->qtrperiod == 2 && $months == "Aug"){
                $attendace->$months = $attendace->$months + $value;
        }
        elseif((!$check->isEmpty()) && $quarter->qtrperiod == 3 && $months == "Oct"){
                $attendace->$months = $attendace->$months+$value;
        }
        elseif((!$check->isEmpty()) && $quarter->qtrperiod == 4 && $months == "Dec"){
            $attendace->$months = $attendace->$months+$value;
        }else{
            $attendace->$months = $value;
        }
        $attendace->save();
        
        return null;
    }    
}

