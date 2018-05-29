<?php

namespace App\Http\Controllers\StudentPromotion;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Http\Controllers\Registrar\Helper as RegistrarHelper;

class PromotionController extends Controller
{
    public function __construct(){
        $this->middleware('auth',['except'=>array('viewreport','updatestudentProm')]);
    }
    
    function index($sy){
        
        $currSY = \App\CtrYear::where('type','enrollment_year')->first()->year;
        $levels = \App\CtrLevel::get();
        return view('registrar.promotion.index',compact('sy','currSY','levels'));
    }
    
    function viewreport(){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $section = Input::get('section');
        $students = $this->students($sy,$level,$section);
        return view('ajax.promotionview',compact('students','sy'));
    }
    
    function editpromotion($sy,$level,$section){
        $admissions= \App\CtrStudentPromotion::where('type','admission')->get();
        $probations = \App\CtrStudentPromotion::where('type','!=','admission')->get();
        $students = $this->students($sy-1,$level,$section);
        $this->createInitialRecord($students, $sy);
        
        return view('registrar.promotion.edit',compact('students','section','sy','level','admissions','probations'));
    }
    
    function createInitialRecord($students,$sy){
        foreach($students as $student){
            $promotionStat = \App\StudentPromotion::where('schoolyear',$sy)->where('idno',$student->idno)->first();
            $isnew = RegistrarHelper::isNewStudent($student->idno,$sy-1);
            
            if(!$promotionStat){
                if($isnew){
                    $this->updateStatus($student->idno,'admission','',$sy);
                }else{
                    $this->updateStatus($student->idno,'admission','PI',$sy);
                }
            }
        }
    }
    

    
    function updateStatus($idno,$type,$value,$schoolyear){
        $record = \App\StudentPromotion::where('schoolyear',$schoolyear)->where('idno',$idno)->first();
        if($record){
            $record->$type = $value;
            $record->save();
        }else{
            $newRecord = new \App\StudentPromotion();
            $newRecord->idno = $idno;
            $newRecord->schoolyear = $schoolyear;
            $newRecord->$type = $value;
            $newRecord->save();
            
        }
        
    }
    
    function updatestudentProm(){
        $idno = Input::get('idno');
        $type = Input::get('type');
        $value= Input::get('value');
        $schoolyear = Input::get('sy');
        
        $this->updateStatus($idno,$type,$value,$schoolyear);
    }
    
    function students($sy,$level,$section){
        $currSy = \App\CtrYear::where('type','schoolyear')->first()->year;
        if($currSy == $sy){
            //$table = 'statuses';
            $students = \App\Status::with('user')->whereIn('status',array(2,3))->where('level',$level)->where('schoolyear',$sy)->where('section','!=','')->orderBy('class_no')->get();
        }
        else{
            //$table = 'status_histories';
            $students = \App\StatusHistory::with('user')->whereIn('status',array(2,3))->where('level',$level)->where('schoolyear',$sy)->where('section','!=','')->orderBy('class_no')->get();
        }
        
        if($section != "All"){
            $students = $students->where('section',$section)->sortBy(function($items){
                return $items->user->lastname."-".$items->user->firstname;
            });
        }
        
        return $students;
    }
}
