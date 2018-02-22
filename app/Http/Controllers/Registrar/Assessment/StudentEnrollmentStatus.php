<?php

namespace App\Http\Controllers\Registrar\Assessment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Assessment\Helper;

class StudentEnrollmentStatus extends Controller
{
    static function enrollmentStatus($idno){
        $status = self::isCurrentlyEnrolled($idno);
        $withPrev = self::withPrevBalance($idno);
        $withLiab = self::withLiability($idno);
        
        $message  = $withPrev['message'].", ".$withLiab['message'];
        echo $message;
        if($status || $withPrev['result'] || $withLiab['result']){
            return collect(array('result'=>FALSE,'message'=>$message));
        }else{
            return collect(array('result'=>TRUE,'message'=>$message));
        }
    }
    
    static function isoldStudent($idno){
        $enrollmentSY = Helper::get_enrollmentyear();
        $status = \App\Status::where('idno',$idno)->where('schoolyear','<',$enrollmentSY)->whereIn('status',array(2,3))->first();
        
        if($status){
            $status = \App\StatusHistory::where('idno',$idno)->where('schoolyear','<',$enrollmentSY)->whereIn('status',array(2,3))->first();
        }
        
        return $status;
    }
    
    static function isCurrentlyEnrolled($idno){
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $status = \App\Status::where('idno',$idno)->where('schoolyear',$schoolyear)->where('status',2)->first();
        
        return $status;
    }
    
    static function withPrevBalance($idno){
        $prevbal = 0;
        $schoolyear = Helper::get_enrollmentyear();
        
        $ledger = \App\Ledger::where('idno',$idno)->where('schoolyear','<',$schoolyear)->get();
        
        if($ledger){
            $prevbal = $ledger->sum('amount')-($ledger->sum('payment')+$ledger->sum('debitmemo')+$ledger->sum('plandiscount')+$ledger->sum('otherdiscount'));
        }
        
        if($prevbal > 0){
            return collect(array('result'=>true,'message'=>'With remaining account from last year.'));
        }else{
            return collect(array('result'=>false,'message'=>''));
        }
        
        
    }
    
    static function withLiability($idno){
        $schoolyear = Helper::get_enrollmentyear();
        $promotion = \App\StudentPromotion::where('idno',$idno)->where('schoolyear','<',$schoolyear)->first();
        $promotionval = \App\CtrStudentPromotion::get();
        
        $liable = false;
        
        if($promotion){
            if($promotion->conduct == $promotionval->where('type','conduct',false)->last()->code){
               $liable = TRUE; 
            }
            if($promotion->academic == $promotionval->where('type','acad',false)->last()->code){
               $liable = TRUE; 
            }
            if($promotion->technical == $promotionval->where('type','tech',false)->last()->code){
               $liable = TRUE; 
            }
        }
        
        if($liable){
            return collect(['result'=>$liable,'message'=>'With Very Strict Probation']);
        }else{
            return collect(['result'=>$liable,'message'=>'']);
        }
    }
    
}
