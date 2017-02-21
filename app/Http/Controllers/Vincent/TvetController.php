<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Excel;

class TvetController extends Controller
{
    function tvetledger(){
        $schoolyear = \App\CtrRefSchoolyear::first();
/*        $ledgers = DB::Select("select amount,sponsor,subsidy,lastname,firstname,middlename,extensionname,tvet_subsidies.batch,course "
                . "from ledgers "
                . "join users on users.idno = ledgers.idno "
                . "join tvet_subsidies on ledgers.idno = tvet_subsidies.idno "
                . "and ledgers.period = tvet_subsidies.batch "
                . "where users.idno = '$idno' "
                . "and ledgers.schoolyear = '$schoolyear->schoolyear'");*/
          $batches = \App\ctrSchoolYear::where('department','TVET')->where('schoolyear',$schoolyear->schoolyear)->get();
          $courses = DB::Select("select distinct course from ctr_subjects where department = 'TVET'");
        return view('vincent.tvet.TVETLedger',compact('batches','courses'));
    }
    
    function getsectionstudent($batch,$cours,$section){
        $schoolyear = \App\CtrRefSchoolyear::first();
        $students = $this->studentlist($batch,$cours,$section);
        $batches = \App\ctrSchoolYear::where('department','TVET')->where('schoolyear',$schoolyear->schoolyear)->get();
        $courses = DB::Select("select distinct course from ctr_subjects where department = 'TVET'");        
        
        return view('vincent.tvet.TVETLedger',compact('batches','courses','batch','cours','section','students'));
    }
    
    function edittvetcontribution($batch,$cours,$section){
        $schoolyear = \App\CtrRefSchoolyear::first();
        $studentledgers = $this->studentlist($batch,$cours,$section);
        $batches = \App\ctrSchoolYear::where('department','TVET')->where('schoolyear',$schoolyear->schoolyear)->get();
        $courses = DB::Select("select distinct course from ctr_subjects where department = 'TVET'");        
        
        return view('vincent.tvet.TVETLedger',compact('batches','courses','batch','cours','section','studentledgers'));
    }    
    
    function studentlist($batch,$course,$section){
        if($batch == 87){
            $batch = '1st Batch';
        }
        $students = DB::Select("Select class_no,remarks,tvet_subsidies.discount,statuses.period,users.idno,firstname,lastname,middlename,extensionname,subsidy,discount,sponsor,amount "
                . "from users "
                . "join statuses on users.idno = statuses.idno "
                . "join ledgers on ledgers.idno = statuses.idno and ledgers.period = statuses.period "
                . "join tvet_subsidies on tvet_subsidies.idno = ledgers.idno and tvet_subsidies.batch = ledgers.period "
                . "where statuses.period = '$batch' and statuses.course = '$course' and statuses.section = '$section' "
                . "order by class_no,lastname, firstname");
        
        return $students;
    }
    
    function savetvetChanges(Request $request,$batch,$cours,$section){
        
        $this->checkLog($request->all(),$batch,$cours,$section);

       return Redirect::to('/studentsledger/'.$batch.'/'.$cours.'/'.$section);
    }
    
    function checkLog(array $request,$batch,$cours,$section){
        $students = $this->studentlist($batch,$cours,$section);
        $nos = count($students);
        /*
        $fields = array ();
        for($index = 1;$nos>=$index;$index++){

            $new_subsidy = $request['subsidy'.$index];
            $new_sponsor = $request['sponsor'.$index];
            $new_trainee = $request['trainees'.$index];
            $desc = $request['desc'.$index];
            
            $ledger = \App\Ledger::where('idno',$request['idno'.$index])->where('period',$batch)->first();
            $subsidies = \App\TvetSubsidy::where('idno',$request['idno'.$index])->where('batch',$batch)->first();        
            $name = "desc".$index;
            if($ledger->amount != $new_trainee ||$subsidies->subsidy != $new_subsidy || $subsidies->sponsor != $new_sponsor){
                $fields[$name] = "required";
            }
        }

        $messages = array(
            'required' => 'This field is required when creating changes.'
        );      
        $validator = Validator::make(Input::all(), $fields,$messages);
        
        if ($validator->fails())
        {
            return Redirect::to('/studentsledger/'.$batch.'/'.$cours.'/'.$section.'/edit')->withErrors($validator);
        }else{
        */
        if($batch == 87){
            $batch = '1st Batch';
        }        
        for($index = 1;$nos>=$index;$index++){
            $student = $request['idno'.$index];
            $new_subsidy = $request['subsidy'.$index];
            $new_sponsor = $request['sponsor'.$index];
            $new_trainee = $request['trainees'.$index];
            $desc = $request['desc'.$index];
            
            $ledger = \App\Ledger::where('idno',$request['idno'.$index])->where('period',$batch)->first();
            $subsidies = \App\TvetSubsidy::where('idno',$request['idno'.$index])->where('batch',$batch)->first();        
            
            if($subsidies->remarks != $desc || $ledger->amount != $new_trainee ||$subsidies->subsidy != $new_subsidy || $subsidies->sponsor != $new_sponsor){
                if($ledger->amount != $new_trainee ||$subsidies->subsidy != $new_subsidy || $subsidies->sponsor != $new_sponsor){
                    $this->savelog($student,$batch,$new_subsidy,$new_sponsor,$new_trainee,$desc,$ledger,$subsidies);
                }
                $this->savechanges($student,$batch,$new_subsidy,$new_sponsor,$new_trainee,$desc);
            }
        } 
        //}
        return null;
    }
    
    function savelog($student,$batch,$new_subsidy,$new_sponsor,$new_trainee,$desc,$ledger,$subsidies){

//            $date = date("F",strtotime("-1 months"));

            $check1 = \App\TvetRecordChange::where('idno',$student)->where('batch',$batch)->get();
            if($check1->isEmpty()){
               $log = new \App\TvetRecordChange;
               $log->idno = $student;
               $log->new_subsidy = $new_subsidy;
               $log->new_sponsor = $new_sponsor;
               $log->new_contribution = $new_trainee;
               $log->old_subsidy = $subsidies->subsidy;
               $log->old_sponsor = $subsidies->sponsor;
               $log->old_contribution = $ledger->amount;
               $log->batch = $batch;
               $log->logdate = date("Y-m-d");
               $log->original = 1;
               $log->save();         
            }else{
                $check2 = \App\TvetRecordChange::where('idno',$student)->where('batch',$batch)->where('logdate',date("Y-m-d"))->get();
                if($check2->isEmpty()){
                    $log = new \App\TvetRecordChange;
                }else{
                    $log = \App\TvetRecordChange::where('idno',$student)->where('batch',$batch)->where('logdate',date("Y-m-d"))->first();    
                }
               $log = new \App\TvetRecordChange;
               $log->idno = $student;
               $log->new_subsidy = $new_subsidy;
               $log->new_sponsor = $new_sponsor;
               $log->new_contribution = $new_trainee;
               $log->old_subsidy = $subsidies->subsidy;
               $log->old_sponsor = $subsidies->sponsor;
               $log->old_contribution = $ledger->amount;
               $log->batch = $batch;
               $log->logdate = date("Y-m-d");
               $log->save();                 
            }


           

     return null;
 }            
 
    function savechanges($student,$batch,$new_subsidy,$new_sponsor,$new_trainee,$desc){
        
        $ledger = \App\Ledger::where('idno',$student)->where('period',$batch)->first();
        $ledger->amount = $new_trainee;
        $ledger->save();
        
        $subsidies = \App\TvetSubsidy::where('idno',$student)->where('batch',$batch)->first();
        $subsidies->sponsor = $new_sponsor;
        $subsidies->subsidy = $new_subsidy;
        $subsidies->remarks = $desc;
        $subsidies->save();
        
        return null;
    }
    
    function changecourses(Request $request,$batch,$idno){
        $status = \App\Status::where('period',$batch)->where('idno',$idno)->first();
        $status->course = $request->course;
        $status->save();
        
        $ledger = \App\Ledger::where('period',$batch)->where('idno',$idno)->first();
        $ledger->course = $request->course;
        $ledger->save();
        
        return Redirect::back();

    }
    
    function enrollmentreport(){
        $batches = \App\ctrSchoolYear::where('department','TVET')->get();
        
        return view('vincent.tvet.tvetEnrollmentreport',compact('batches'));
    }
    
    function download($batch){
        Excel::create('MIS 03-02 Form', function($excel) {

            $excel->sheet('AMC-A', function($sheet){
                $sheet->loadView('vincent.export.enrollmentReport');
                
                $sheet->mergeCells('AL1:AM2');
                $sheet->setBorder('A1:AT4', 'double');
                
            });
        })->export('xls');
    }
}
