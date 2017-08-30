<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Excel;
use App\Http\Requests;

class GradeSubmitController extends Controller
{
    function index(){
        return view('vincent.academic.uploadgrade');
    }
    
    function importgrade(Request $request){
        if(Input::hasFile('import_file')){
            $test=6;
            $path = Input::file('import_file')->getRealPath();
             Excel::selectSheets('Registrar')->load($path, function($reader) use ($test){
                $uploaded = array();
                do{
                    $idno = $reader->getActiveSheet()->getCell('B'.$test)->getOldCalculatedValue();
                    
                    if(strlen($idno)<6){
                        $idno = "0".$idno;
                    }
                    $grade = $reader->getActiveSheet()->getCell('F'.$test)->getOldCalculatedValue();
                    $uploaded[] = array('idno'=>$idno,'grade'=>$grade);
                    $test++;
                }while(strlen($reader->getActiveSheet()->getCell('B'.$test)->getOldCalculatedValue())>5);
                
                session()->flash('grades', $uploaded);
                
            });
            $grades = session('grades');
                return view('vincent.academic.uploadgrade',compact('grades','request'));
        }
    }
    
    function saveentry(Request $request){
        $sy = \App\CtrRefSchoolyear::first();
        $grades = $request->input('student');
        foreach($grades as $key=>$value){
            if($value != "" || $value != null || preg_match('/^[0-9]*$/', $value)){
                $grade = \App\Grade::where('idno',$key)->where('subjectcode',$request->subj)->where('schoolyear',$sy->schoolyear)->first();
                if(!empty($grade)){
                    $grade->fourth_grading = $value;
                    $grade->save();
                }                
            }

        }
        
        return redirect()->back();
        
    }
}
