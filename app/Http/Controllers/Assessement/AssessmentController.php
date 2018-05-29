<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StudentInfo as Info;
use App\Http\Controllers\Helper as MainHelper;
use App\Http\Controllers\Assessement\EnrollmentStatus;
class AssessmentController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
            
    function view($idno){
        $lastEnrollmentYear = MainHelper::enrollment_prevSchool();
        $currEnrollment = MainHelper::get_enrollmentSY();
        $lastYearAStudent = Info::get_StudentSyInfo($idno, $lastEnrollmentYear);
        
        if(in_array(Info::get_status($idno, $currEnrollment),array(1,2,3))){
            return view('assess.assessed',compact('currEnrollment','idno'));
        }
        
        if($lastYearAStudent){
            return $this->lastYearAStudentView($idno, $lastYearAStudent);
        }else{
            $returning = \App\StatusHistory::where('idno',$idno)->where('schoolyear','<',$lastEnrollmentYear)->whereIn('status',array(2,3))->orderBy('schoolyear','DESC')->first();
            return $this->returneeOrNewView($idno,$returning);
        }
    }
    
    function returneeOrNewView($idno,$laststatus){
        $levels = \App\CtrLevel::all();
        $discounts = \App\CtrDiscount::groupBy('series')->get();
            
        return view('assess.freeformassess',compact('laststatus','idno','discounts','levels'));
    }
    
    function lastYearAStudentView($idno,$laststatus){
        
        $isEligble = EnrollmentStatus::isEligible($idno, $laststatus->schoolyear);
        if($isEligble){
            $discounts = \App\CtrDiscount::groupBy('series')->get();
            return view('assess.assess',compact('laststatus','idno','discounts'));
        }
    }
    
    function printAssessement($idno){
        
        $schoolyear = MainHelper::get_enrollmentSY();
        
        $mainaccounts = \App\Ledger::where('idno',$idno)->where('schoolyear',$schoolyear)->orderBy('categoryswitch','ASC')->orderBy('duedate','ASC')->get();
        
       $pdf = \App::make('dompdf.wrapper');
       $pdf->setPaper('letter', 'portrait');
        $pdf->loadView("assess.printassessment",compact('idno','schoolyear','mainaccounts'));
       return $pdf->stream();
    }
}
