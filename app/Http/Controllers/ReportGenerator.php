<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ReportGenerator extends Controller
{
   function randomStudentLedger(){

       return view('randomReports');
   }
   
   static function getLevelStudents($level){
      $status = \App\Status::where("level",$level->level)->whereIn('status',array(2))->get();
      return $status;
   }
   
   static function getRandomStudent($students,$number){
       $studentsledger = self::studentswithBalace($students);
       
       $student = $studentsledger->shuffle()->first();
       
       return $student;
   }
   
   static function studentswithBalace($students){
       $idnos = $students->pluck('idno')->toArray();
       $ledger = \App\Ledger::whereIn('idno',$idnos)->where('schoolyear',2017)->get()->groupBy('idno');
       
       return $ledger;
   }
}
