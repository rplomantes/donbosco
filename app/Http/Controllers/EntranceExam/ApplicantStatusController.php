<?php

namespace App\Http\Controllers\EntranceExam;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ApplicantStatusController extends Controller
{
    function updateStatus(){
        $levels = \App\CtrLevel::all();
        return view('EntranceExam.applicantStatus',compact('levels'));
    }
    
    function changeStatus(){
        $id = Input::get('id');
        $status = Input::get('status');
        
        $statusupdate  = \App\ApplicantStatus::where('application_id',$id)->first();
        $statusupdate->status = $status;
        $statusupdate->save();
    }
}
