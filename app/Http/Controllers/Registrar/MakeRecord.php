<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MakeRecord extends Controller
{
    function createRecord($idno){
        $schools = \App\PrevSchoolRec::distinct('school')->pluck('school')->toArray();
        foreach($schools as $key=>$value){
          $schools[$key]=str_replace('"','\"',$value);
        }
        return view('registrar.createRec',compact('idno','schools'));
    }
    
    function saveRecord(Request $request){
        $newrecord = new \App\PrevSchoolRec;
        $newrecord->idno = $request->idno;
        $newrecord->schoolyear = $request->schoolyear;
        $newrecord->school = $request->school;
        $newrecord->level = $request->level;
        $newrecord->finalrate = $request->grade;
        $newrecord->dayp = $request->dayp;
        $newrecord->created_by = \Auth::user()->idno;
        $newrecord->save();
        
        return redirect('/seegrade/'.$request->idno);
        
    }
}
