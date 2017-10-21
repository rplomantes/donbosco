<?php

namespace App\Http\Controllers\EntranceExam;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class Helper extends Controller
{
    static function schedPerLevel($level){
        $examSy = \App\CtrRegistrationSchoolyear::first()->schoolyear;
        $scheds = \App\EntranceSchedule::where('schoolyear',$examSy)->where('level',$level)->orderBy('id','ASC')->get();
        
        return $scheds;
    }
    
    static function schedApplicant($schedId){
        $applicants = \App\EntranceApplicant::where('schedule_id',$schedId)->get()
                ->sortBy(function($applicants) { 
                return $applicants->user->lastname;})
                ->sortBy(function($applicants) { 
                return $applicants->user->firstname;});
        
        return $applicants;
    }
            
    function createSched($level){
        $examSy = \App\CtrRegistrationSchoolyear::first()->schoolyear;
        $scheds = self::schedPerLevel($level);
        $batch = count($scheds) + 1;
        
        $newSched = new \App\EntranceSchedule();
        $newSched->batch = "Batch ".$batch;
        $newSched->level = $level;
        $newSched->schoolyear = $examSy;
        $newSched->save();
    }
    
    function deleteSched($id){        
        $newSched = \App\EntranceSchedule::find($id);
        $newSched->delete();
    }
    
    function updateSched(){
        $type = Input::get('type');
        $id = Input::get('entry');
        $data = Input::get('data');
        
        $newSched = \App\EntranceSchedule::find($id);
        switch($type){
            case 1:
                $date = date('Y-m-d',  strtotime($data));
                $newSched->date = $date;
                break;
            case 2:
                $newSched->max_examinee = $data;
                break;
            case 3:
                $newSched->time_start = $data;
                break;
            case 4:
                $newSched->time_end = $data;
                break;
        }
        $newSched->save();
    }
    
    function getLevelSchedule($level){
        $scheds = self::schedPerLevel($level);
        
        return view('ajax.entranceLevelSchedule',compact('scheds'));
    }
}
