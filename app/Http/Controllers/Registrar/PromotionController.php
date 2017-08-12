<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
class PromotionController extends Controller
{
    function index($sy){
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        $levels = \App\CtrLevel::get();
        return view('registrar.promotion.index',compact('sy','currSY','levels'));
    }
    
    function viewreport($sy,$level){

        $students = $this->students($sy,$level);
        return view('ajax.promotionview',compact('students'));
    }
    
    function editpromotion($sy,$level){
        $admissions= \App\CtrStudentPromotion::where('type','admission')->get();
        $probations = \App\CtrStudentPromotion::where('type','!=','admission')->get();
        $students = $this->students($sy,$level);
        return view('registrar.promotion.edit',compact('students','sy','level','admissions','probations'));
    }
    
    function savepromotion($sy,$level){
        $students = $this->students($sy,$level);
        
        foreach($students as $student){
            $conduct = Input::get('conduct.'.$student->studno);
            if($conduct === null){
                $conduct = "";
            }
            $academic = Input::get('acad.'.$student->studno);
            if($academic === null){
                $academic = "";
            }
            $technical = Input::get('tech.'.$student->studno);
            if($technical === null){
                $technical = "";
            }
            $admission = Input::get('admission.'.$student->studno);
            
            self::createOrUpdate($student->studno,$sy,$admission,$conduct,$academic,$technical);
        }
        
        return "me";
    }
    
    function  createOrUpdate($idno,$sy,$admission,$conduct,$academic,$technical){
        $exist = \App\StudentPromotion::where('idno',$idno)->where('schoolyear',$sy)->exists();
        
        if($exist){
            $student = \App\StudentPromotion::where('idno',$idno)->where('schoolyear',$sy)->first();
            
        }else{
            $student = new \App\StudentPromotion();
            $student->idno = $idno;
            $student->schoolyear = $sy;
        }
        
        $student->admission = $admission;
        $student->conduct = $conduct;
        $student->academic = $academic;
        $student->technical = $technical;
        $student->save();
        
        echo $student;
        
    }
            
    function students($sy,$level){
        $currSy = \App\RegistrarSchoolyear::first()->schoolyear;
        if($currSy == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        $students = DB::Select("Select *,u.idno studno from $table s "
                . "join users u on u.idno = s.idno "
                . "left join student_promotions sp on s.idno = sp.idno "
                . "and s.schoolyear = sp.schoolyear "
                . "where s.level = '$level' "
                . "AND s.status IN (2,3)"
                . "AND s.schoolyear = $sy "
                . "Order by lastname ASC,firstname ASC,middlename ASC");
        return $students;
    }
    
    static function selected($student_conduct,$student_academic,$student_technical,$probation_type,$probation_code){
        if($probation_type == 'conduct'){
            if($probation_code == $student_conduct){
                return true;
            }else{
                return false;
            }
        }elseif($probation_type == 'acad'){
            if($probation_code == $student_academic){
                return true;
            }else{
                return false;
            }
        }elseif($probation_type == 'tech'){
            if($probation_code == $student_technical){
                return true;
            }else{
                return false;
            }
        }
    }
}
