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
   
   //Maam Vicky
   function preFiscalEnrollee($beforedate,$schoolyear){
       $sort = \App\CtrLevel::orderBy('id')->pluck('level')->toArray();
       
       $students = \App\Status::where('schoolyear',$schoolyear)->where('date_enrolled','<',$beforedate)->whereIn('status',array(2,3))->where('department','NOT LIKE','TVET')->get()->sortBy(function($model)use($sort){
           return array_search($model->level, $sort);
       });
       
       return view('vincent.tools.preFiscalEnrollee',compact('students'));
       //return $students;
   }
   
   static function getTuition($idno,$schoolyear){
       $tuition = \App\Ledger::where('idno',$idno)->where('schoolyear',$schoolyear)->where('categoryswitch',6)->get();
       $amount = $tuition->sum('amount');
       $discount = $tuition->sum('plandiscount')+$tuition->sum('otherdiscount');
       return ['amount'=>$amount,'discount'=>$discount];
       
   }
   
   static function discountapplication($account,$schoolyear){
       $discounts = \App\CtrDiscount::where('accountingcode',$account)->get()->pluck('discountcode')->toArray();
       
       $sort = \App\CtrLevel::orderBy('id')->pluck('level')->toArray();
       $ledger = \App\Ledger::where('schoolyear',$schoolyear)->whereIn('discountcode',$discounts)->get()->sortBy(function($model)use($sort){
           return array_search($model->level, $sort);
       });
       
       return view('vincent.tools.discountapplication',compact('ledger'));
       
   }
}
