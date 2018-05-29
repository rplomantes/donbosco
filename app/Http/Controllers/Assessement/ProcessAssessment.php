<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Assessement\Helper as AssHelper;
use App\Http\Controllers\Helper as MainHelper;
use App\Http\Controllers\StudentInfo as Info;
use App\Http\Controllers\Assessement\PlanController as Plan;
use App\Http\Controllers\Assessement\ProcessStatus as Stat;
use App\Http\Controllers\Assessement\ProcessLedger as Ledr;
use App\Http\Controllers\Assessement\ProcessSubjects as Subjects;
use App\Http\Controllers\Assessement\ProcessReservation as Reserve;
use App\Http\Controllers\Assessement\ProcessDeposit as Deposit;
use App\Http\Controllers\Assessement\ProcessBooks as Books;
use App\Http\Controllers\Assessement\ProcessAwards as Awards;
use Illuminate\Support\Facades\DB;


class ProcessAssessment extends Controller
{
    function save($idno,Request $request){
        DB::transaction(function () use($idno,$request){
            $requestStrand = $request->input('strand');
            $plan = $request->input('plan');
            $books = $request->input('books');

            $info = $this->validateInfo($idno, $requestStrand);
            $level = $info['level'];
            $strand = $info['strand'];
            
            if(Plan::isPlanValid($plan, $level, $strand)){
                Stat::process_Status($idno,$level,$strand,$plan);
                Ledr::processLedger($idno, $level, $strand, $plan);
                if(count($books)>0){
                    Books::processBooks($books, $idno);
                }
                Reserve::activateReservation($idno);
                Deposit::activateDeposit($idno);
                Subjects::processSubjects($idno, $level, $strand);   
                Awards::changeAwardStatus($idno, 1);

            }
        });
        DB::commit();
        
        return redirect()->route('assessment',array($idno));
    }
    
    function validateInfo($idno,$strand){
        $lastEnrollmentYear = MainHelper::enrollment_prevSchool();
        $lastYearAStudent = Info::get_StudentSyInfo($idno, $lastEnrollmentYear);
        
        if($lastYearAStudent){
            $level = AssHelper::level_up($idno,$lastYearAStudent->level);
            if($strand ==""){
                $strand = AssHelper::get_hasStrand($level,$idno,$lastEnrollmentYear);
            }
            
        }else{
            $returning = \App\StatusHistory::where('idno',$idno)->where('schoolyear','>',$lastEnrollmentYear)->whereIn('status',array(2,3))->orderBy('schoolyear','DESC')->first();
            if($returning){
                $level = AssHelper::level_up($idno,$returning->level);
                if($strand ==""){
                    $strand = AssHelper::get_hasStrand($level,$idno,$returning->schoolyear);
                }
                
            }else{
                $level = "";
            }
        }
        
        return ["level"=>$level,"strand"=>$strand];
    }
    
    function reassess($idno,Request $request){
            $schoolyear = MainHelper::get_enrollmentSY();

            Stat::chageStatus($idno, $schoolyear,0);
            Ledr::deleteLedger($idno, $schoolyear);
            Reserve::deactivateReservation($idno);
            Subjects::deleteSubjects($idno, $schoolyear);
            Awards::changeAwardStatus($idno, 0);
            
            return redirect()->route('assessment',array($idno));
        }
}
