<?php

namespace App\Http\Controllers\Registrar\PermanentRec;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    static function getLevelInfo($level,$idno){
       $section ="";
       $school ="";
       $sy ="";
       
       $transferee = \App\PrevSchoolRec::where('level',$level)->where('idno',$idno)->first();
       
       if(count($transferee)> 0 ){
           $section = $transferee->section;
           $school = $transferee->school;
           $sy = $transferee->schoolyear;
       }else{
           $status = self::oldStudent($level,$idno);
           
           if(count($status)>0){
            $section = $status->section;
            $school = "DON BOSCO TECHNICAL INSTITUTE";
            $sy = $status->schoolyear;
           }
       }
       
       return ['section'=>$section,'school'=>$school,'sy'=>$sy];
    }
    
    static function oldStudent($level,$idno){
        $stat = \App\Status::where('level',$level)->where('idno',$idno)->orderBy('schoolyear','DESC')->first();
        
        if(!$stat){
            $stat = \App\StatusHistory::where('level',$level)->where('idno',$idno)->orderBy('schoolyear','DESC')->first();
        }
        
        return $stat;
    }
}
