<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function __construct(){
	$this->middleware('auth');
    }

   static function studentQuarterAttendance($idno,$sy,$quarter,$level){
        if($sy == 2016){
            $qtrattendance = self::compute2016Attend($idno,$sy,$quarter,$level);
        }else{
            return null;    
        }
        
        return $qtrattendance;
    }
    
    static function compute2016Attend($idno,$sy,$quarter,$level){
        
        $months = self::attendance2016Reconstruct($quarter,$sy,$idno,$level);
        $month1 = $months[0];
        $month2 = $months[1];
        $month3 = $months[2];
        
        if(!empty($month1) && !empty($month2) && !empty($month3)){
            $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
            $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
            $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
        }else{
            $dayt = 0;
            $dayp = 0;
            $daya = 0;
        }
        
        return array($dayp,$daya,$dayt);
    }
    
    static function attendance2016Reconstruct($quarter,$sy,$idno,$level){
        switch ($quarter){
            case 1;
                    $month1 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$idno)->where('schoolyear',$sy)->where('month','JUN')->orderBy('id','DESC')->first();
                    $month2 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$idno)->where('schoolyear',$sy)->where('month','JUL')->orderBy('id','DESC')->first();
                    $month3 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$idno)->where('schoolyear',$sy)->where('month','AUG')->orderBy('id','DESC')->first();
            break;
            case 2;
                    $month1 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"Sept")->orderBy('id','DESC')->first();
                    $month2 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"OCT")->orderBy('id','DESC')->first();
                    $month3 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"AUG")->orderBy('id','DESC')->first();
            break;                
            case 3;
                if(strcmp($level, "Grade 11")>0 || strcmp($level, "Grade 12")>0){
                    $month1 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"OCT")->orderBy('id','DESC')->first();
                    $month2 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"NOV")->orderBy('id','DESC')->first();
                    $month3 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"DECE")->orderBy('id','DESC')->first();
                }else{
                    $month1 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"NOV")->orderBy('id','DESC')->first();
                    $month2 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"DECE")->orderBy('id','DESC')->first();
                    $month3 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"JAN")->orderBy('id','DESC')->first();                            
                }
            break;
            case 4;
                $month1 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"JAN")->orderBy('id','DESC')->first();
                $month2 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"FEB")->orderBy('id','DESC')->first();
                $month3 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$idno)->where('schoolyear',$sy)->where('month',"MAR")->orderBy('id','DESC')->first();
            break;                
        }
        
        return array($month1,$month2,$month3);
    }
    

}
