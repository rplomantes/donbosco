<?php

namespace App\Http\Controllers\EntranceExam;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

class AssignExaminee extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }
    
    function index(){
        $levels = \App\CtrLevel::all();
        $idno = session('idno');
        return view('EntranceExam.assignApplicant',compact('levels','idno'));
    }
    
    function save(Request $request){
        $request->session()->flash('idno', $request->idno);
        $this->validate($request,[
            'sched' => 'required',
        ]);
        
        $schedule = $request->sched;  
        
        $schedList = \App\EntranceApplicant::where('schedule_id',$schedule)->get();
        $applicant_no = count($schedList)+1;
        
        $setSched = new \App\EntranceApplicant();
        $setSched->schedule_id = $schedule;
        $setSched->idno = $request->idno;
        $setSched->applicant_id = $applicant_no;
        $setSched->save();
        
        $applicantStat = new \App\ApplicantStatus();
        $applicantStat->application_id = $setSched->id;
        $applicantStat->save();
        
        return redirect('/');
    }
}
