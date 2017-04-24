<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
class SheetBController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function finalSheetB($quarter,$level,$section,$strand = null){
        $sy = \App\CtrRefSchoolyear::first();
        $strands = Input::get('strand');
        if($strands == null || $strands == '' || $level == 'Grade 7' || $level == 'Grade 8' || $level == 'Grade 9' || $level == 'Grade 10'){
            $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
            $students = \App\StatusHistory::where('level',$level)->where('section',$section)->where('schoolyear','2016')->orderBy('class_no','ASC')->get();
        }else{
            $subjects = \App\CtrSubjects::where('level',$level)->where('strand',$strands)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
            $students = \App\StatusHistory::where('level',$level)->where('section',$section)->where('strand',$strands)->where('schoolyear','2016')->orderBy('class_no','ASC')->get();
        }
        
        return view("ajax.finalSheetB",compact('quarter','students','subjects','level','section'));
        //return $subjects;
    }
}
