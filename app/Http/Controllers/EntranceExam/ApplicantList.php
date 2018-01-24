<?php

namespace App\Http\Controllers\EntranceExam;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\EntranceExam\Helper as EntranceHelper;

class ApplicantList extends Controller
{
   
   function index(){
       $levels = \App\CtrLevel::all();
       
       return view('EntranceExam.applicantList',compact('levels'));
   }
   
   function updateview(){
       $rows = intval(Input::get('rows'))-1;
       $id = Input::get('sched');
       
       $applicantion = EntranceHelper::schedApplicant($id);
       $data = "";
       if(count($applicantion) > $rows){
           $get = count($applicantion)- $rows;
           $applicants = \App\EntranceApplicant::limit(count($applicantion))->skip($rows)->where('schedule_id',$id)->get();

           foreach($applicants as $applicant){
               $data = $data."<tr><td>".$applicant->applicant_id."</td><td>".$applicant->user->lastname.", ".$applicant->user->firstname." ".$applicant->user->middlename."</td></tr>";
           }
       }
       return $data;
       
   }
}
