<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Vincent\AttendanceFixer as AttRec;

class AttendanceFixHS extends Controller
{
    function view($level){
        

            $sections = \App\CtrSection::where('level',$level)->groupBy('id')->get();
            foreach($sections as $section){
                $this->students($level,$section->section);
            }
    }
    
    function students($level,$section){
        $statuses = \App\Status::where('level',$level)->where('section',$section)->where('schoolyear',2017)->groupBy('class_no')->get();
        foreach($statuses as $status){
            echo $status->idno;
            $this->studentAtt($status);
        }
    }
    
    function studentAtt($status){
        //$attendances = \App\AttendanceRepo::where('idno',$status->idno)->where('schoolyear',$status->schoolyear)->where('qtrperiod',3)->where('month','Jan')->get();
        
        $attendance = \App\Attendance::where('idno',$status->idno)->where('schoolyear',$status->schoolyear)->where('quarter',2)->where('attendancetype','DAYP')->first();
        
        $attendance->Oct = $attendance->Oct + 11;
//        foreach($attendances as $attendance){
//            //DAYP
//            $this->placeAtt($attendance,'DAYP',$attendance->DAYP);
//            
//            //DAYT
//            $this->placeAtt($attendance,'DAYT',$attendance->DAYT);
//            
//            //DAYA
//            $this->placeAtt($attendance,'DAYA',$attendance->DAYA);
//        }
        
//        foreach($attendances as $attendance){
//            echo $attendance."<br>";
//            switch($attendance->attendancetype){
//                case 'DAYP':
//                    echo $attendance->Oct."<br>";
//                    $this->fixattendance($attendance->idno, $attendance->Oct+2, 'Oct', $attendance->attendancetype, 2, 2017);
//                    break;
//                case 'DAYT':
//                    echo $attendance->Oct."<br>";
//                    $this->fixattendance($attendance->idno, $attendance->Oct, 'Oct', $attendance->attendancetype, 2, 2017);
//                    break;
//                case 'DAYA':
//                    echo $attendance->Oct."<br>";
//                    $this->fixattendance($attendance->idno, $attendance->Oct, 'Oct', $attendance->attendancetype, 2, 2017);
//                    break;
//            }
//        }
//        foreach($attendances as $attendance){
//        $attendance->Oct=0;
//        $attendance->save();
//        }

        
        
        
    }
    
    function fixattendance($idno,$newvalue,$month,$type,$quarter,$schoolyear){
        $attendance = \App\Attendance::where('idno',$idno)
                ->where('schoolyear',$schoolyear)
                ->where('quarter',$quarter)
                ->where('attendancetype',$type)->first();
        
        if($attendance){
            $days = $attendance->$month;
            $attendance->$month = $days + $newvalue;
            $attendance->save();
        }
    }
    
    
    function placeAtt($attendance,$type,$value){
        $month = $attendance->month;
        
        $rec = \App\Attendance::where('idno',$attendance->idno)
                ->where('schoolyear',$attendance->schoolyear)
                ->where('quarter',$attendance->qtrperiod)
                ->where('attendancetype',$type)->first();
        
        if($rec){
            $rec->$month = $value;
            $rec->save();
        }
        else{
            $otherInfo = AttRec::getAttendanceInfo($type);
            
            $newAttendance = new \App\Attendance();
            $newAttendance->idno = $attendance->idno;
            $newAttendance->quarter = $attendance->qtrperiod;
            $newAttendance->attendancetype = $type;
            $newAttendance->attendanceName = $otherInfo['name'];
            $newAttendance->sortto = $otherInfo['order'];
            $newAttendance->$month = $value;
            
            $newAttendance->schoolyear = $attendance->schoolyear;
            $newAttendance->save();   
        }
    }
}
