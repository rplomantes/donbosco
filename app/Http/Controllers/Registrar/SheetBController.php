<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SheetBController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function finalSheetB($quarter,$level,$section,$strand = null){
        if($strand == null){
            $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
            $students = \App\Status::where('level',$level)->where('section',$section)->orderBy('class_no','ASC')->get();
        }else{
            $subjects = \App\CtrSubjects::where('level',$level)->where('strand',$strand)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
            $students = \App\Status::where('level',$level)->where('section',$section)->where('strand',$strand)->orderBy('class_no','ASC')->get();
        }
        
        return view("ajax.finalSheetB",compact('quarter','students','subjects','level','section'));
    }
}
