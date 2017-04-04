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
        $strands = Input::get('strand');
        if($strands == null || $strands == ''){
            $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
            $students = \App\Status::where('level',$level)->where('section',$section)->orderBy('class_no','ASC')->get();
        }else{
            $subjects = \App\CtrSubjects::where('level',$level)->where('strand',$strands)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
            $students = \App\Status::where('level',$level)->where('section',$section)->where('strand',$strands)->orderBy('class_no','ASC')->get();
        }
        
        return view("ajax.finalSheetB",compact('quarter','students','subjects','level','section'));
        //return $subjects;
    }
}