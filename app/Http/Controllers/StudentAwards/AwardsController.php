<?php

namespace App\Http\Controllers\StudentAwards;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AwardsController extends Controller
{
    function addStudent(){
        $enrollmentYear = \App\CtrYear::where('type','enrollment_year')->first()->year;
        
        
        return view('StudentAwardee.addStudent',compact('enrollmentYear'));
    }
    
    function saveStudent(Request $request){
        $enrollmentYear = \App\CtrYear::where('type','enrollment_year')->first()->year;
        
        $newRec = new \App\StudentAwardee();
        $newRec->idno = $request->idno;
        $newRec->level = $request->level;
        $newRec->amount = $request->amount;
        $newRec->schoolyear = $enrollmentYear;
        $newRec->postedBy = \Auth::user()->idno;
        if(in_array($request->level,array('Grade 11','Grade 12'))){
            $newRec->type = 'Voucher';
        }else{
            $newRec->type = 'ESC';
        }
        
        $newRec->save();
        
        return redirect('/registrar/evaluate/'.$request->idno);
    }
    
    static function awards($idno,$onlyActive = false){
        $enrollmentyear = \App\CtrYear::where('type','enrollment_year')->first();
        $fund = \App\StudentAwardee::where('idno',$idno)->where('schoolyear','<=',$enrollmentyear->year)->get();
        
        if($onlyActive){
            $fund = $fund->where('status',1,false);
        }
        
        return $fund;
    }
    
    static function hasRemainingFund($idno,$onlyActive = false){
        $fund = self::awards($idno,$onlyActive);

        $remainingFund = $fund->sum('amount')-$fund->sum('used');
        
        if($remainingFund > 0){
            return true;
        }else{
            return false;
        }
    }
    
    static function getAvailableAwards($idno,$onlyActive = false){
        $types = self::awards($idno,$onlyActive)
                ->filter(function($items){
                    return $items->amount > $items->used;
                });
        return $types;
    }
}
