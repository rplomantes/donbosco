<?php

namespace App\Http\Controllers\Registrar\Statistics;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class Enrollment extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function index($schoolyear){
        return view('registrar.statistics.Enrollment.index',compact('schoolyear'));
    }
    
    static function kto12Enrollment($schoolyear){
        $curr_schoolyear = \App\CtrYear::getYear('schoolyear');
        
        $levels = \App\CtrLevel::all();
        $projection = \App\ProjectionEnrollment::where('schoolyear');
        if($curr_schoolyear == $schoolyear){
            $enrollees = \App\Status::select(DB::raw('idno,level,strand'))->where('status',2)->where('schoolyear',$schoolyear)->get();
            foreach($enrollees as $enrollee){
                $user = \App\User::where('idno',$enrollee->idno)->first();
                $gender = trim(strtoupper($user->gender));
                $enrollee->gender = $gender;
            }
        }
        
        return view('registrar.statistics.Enrollment.kto12Enrollment',compact('levels','enrollees','schoolyear'))->render();
    }
    
    static function tvetEnrollment(){
        $batch = \App\CtrSchoolYear::where('department','TVET')->orderBy('id','DESC')->first();
        
        $enrollees = \App\Status::where('period',$batch->period)
            ->where('status',2)->get();
        
        return view('registrar.statistics.Enrollment.tvetEnrollment',compact('batch','enrollees'))->render();
    }
    
    static function departmentalEnrollment($schoolyear){
        $batch = \App\CtrSchoolYear::where('department','TVET')->orderBy('id','DESC')->first();
        
        //Kto12 Department Only
        $departments = \App\CtrSchoolYear::where('department','!=','TVET')->get();
        $enrollees = \App\Status::select(DB::raw('idno,department'))->where('status',2)
                ->where(function($record)use($schoolyear,$batch){
                    $record->where('schoolyear',$schoolyear)
                            ->orWhere('period',$batch->period);
                })->get();
                
        return view('registrar.statistics.Enrollment.departmentalEnrollment',compact('batch','enrollees','departments'))->render();
    }
    
}
