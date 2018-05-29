<?php

namespace App\Http\Controllers\PortalControl;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GradeView extends Controller
{
    
    function sectionGradeViewControl($level,$section){
        $schoolyear = \App\CtrYear::where('type','schoolyear')->first()->year;
        
        $students = $this->sectionList($schoolyear,$level,$section);
        
        return view('PortalControl.gradeView',compact('students','schoolyear','level','section'));
        
    }
    
    function sectionList($schoolyear,$level,$section){
        $status = \App\Status::where('level',$level)->where('section',$section)->where('schoolyear',$schoolyear)->whereIn('status',array(2,3))->get();
        $status_history = \App\StatusHistory::where('level',$level)->where('section',$section)->where('schoolyear',$schoolyear)->whereIn('status',array(2,3))->get();
        
        $students = $status->union($status_history);
        $students = $students->unique('idno')->sortBy('class_no');
        
        return $students;
    }

}
