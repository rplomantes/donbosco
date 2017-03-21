<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class PenaltyController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function penalties(){
                $duemonths = DB::Select('select distinct plan from statuses');
              return view('accounting.penaltydue',compact('duemonths'));  
            }
        
    function postviewpenalty(Request $request){
            $currentdate= Carbon::now(); 
            $forthemonth = date('M Y',strtotime($currentdate));
            $postings = \App\penaltyPostings::where('duemonth',$forthemonth)->where('plan',$request->plan)->get();
            $schoolyear = \App\CtrRefSchoolyear::first();
            $sy=$schoolyear->schoolyear;
            $levels = \App\CtrLevel::all();
            $plan = $request->plan;
            //non monthly 2
            if($plan=="Monthly 2"){
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.level, statuses.section, statuses.strand, "
                    . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                    . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and statuses.department != 'TVET' and "
                    . " ledgers.duedate <= '$currentdate' and statuses.status='2' and statuses.plan = 'Monthly 2'"
                    . " AND ledgers.acctcode IN ('Tuition Fee','Registration & Other Institutional Fees','Department Facilities')"
                    . " group by statuses.idno, users.lastname, users.firstname, users.middlename, statuses.level, statuses.section, statuses.strand  order by statuses.strand, users.lastname, users.firstname");
            }else{
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.level, statuses.section, statuses.strand, "
                    . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                    . " from users, statuses, ledgers,ctr_sections,ctr_levels where ctr_levels.level = statuses.level and ctr_sections.level = statuses.level and ctr_sections.section = statuses.section and users.idno = statuses.idno and users.idno = ledgers.idno and statuses.department != 'TVET' and "
                    . " ledgers.duedate <= '$currentdate' and statuses.status='2' and ledgers.acctcode like 'Tuition %' and statuses.plan = '$plan'"
                    . " group by statuses.idno, users.lastname, users.firstname, users.middlename, statuses.level, statuses.section, statuses.strand  order by ctr_levels.id ASC, ctr_sections.id ASC, statuses.strand, users.lastname, users.firstname");

            }

            return view('accounting.penalties',compact('sy','levels','currentdate','postings','soasummary','plan','forthemonth'));
            }
 
    function postpenalties(Request $request){
                $findpost = \App\penaltyPostings::where('duemonth',$request->duemonth)->where('plan',$request->plan)->first();
                if(count($findpost)==0){

                $idnumber = $request->idnumber;
                $schoolyear = \App\CtrRefSchoolyear::first();
                $plan=$request->plan;
                $duemonth=$request->duemonth;
                foreach($idnumber as $value){
                    $status=  \App\Status::where('idno',$value)->first();
                    $newpenalty = new \App\Ledger;
                    $newpenalty->idno = $value;
                    $newpenalty->department=$status->department;
                    $newpenalty->level=$status->level;
                    $newpenalty->course=$status->course;
                    $newpenalty->strand=$status->strand;
                    $newpenalty->transactiondate= Carbon::now();
                    $newpenalty->categoryswitch = '7';
                    $newpenalty->accountingcode = '440200';
                    $newpenalty->acctcode="Other Revenue";
                    $newpenalty->description="Misc. Others( Including Penalty - " . date('M Y') .")";
                    $newpenalty->receipt_details="Miscellaneous Others";
                    $newpenalty->amount=$this->addpenalties($value,$plan);
                    $newpenalty->schoolyear=$status->schoolyear;
                    $newpenalty->period=$status->period;
                    if($status->department == 'Elementary' || $status->department == 'Kindergarten'){
                        $newpenalty->acct_department='Elementary Department';
                        $newpenalty->sub_department='Elementary Department';
                    }elseif($status->department == 'Junior High School' || $status->department == 'Senior High School'){
                        $newpenalty->acct_department='High School Department';    
                        $newpenalty->sub_department='High School Department';    
                    }
                    $newpenalty->duedate=Carbon::now();
                    $newpenalty->duetype='0';
                    $newpenalty->postedby=\Auth::user()->idno;
                    $newpenalty->save();
                }
                $addpost = new \App\penaltyPostings;
                $addpost->dateposted=Carbon::now();
                $addpost->plan=$request->plan;
                $addpost->duemonth=$request->duemonth;
                $addpost->postedby=\Auth::user()->idno;
                $addpost->save();
                return view('accounting.successfullyadded');



                // return $request->idnumber;

                }else{
                return "Already Posted";    
                }
            }
 
    function addpenalties($idnumber,$plan){

                $currentdate= Carbon::now();
                if($plan != "Monthly 2"){
                $soasummary = DB::Select("select "
                    . " sum(amount) - sum(payment) - sum(debitmemo) - sum(plandiscount) - sum(otherdiscount) as amount from"
                    . " ledgers where idno = '$idnumber' and "
                    . " duedate <= '$currentdate'  and categoryswitch = '6'");
                } else {
                    $soasummary = DB::Select("select "
                    . " sum(amount) - sum(payment) - sum(debitmemo) - sum(plandiscount) - sum(otherdiscount) as amount from"
                    . " ledgers where idno = '$idnumber' and "
                    . " duedate <= '$currentdate'  and categoryswitch <= '6'");
                }
                foreach($soasummary as $soa){
                    $amount = $soa->amount;
                }

                $penalty = $soa->amount * 0.05;
                if($penalty < 250){
                    $penalty = 250;
                }
                return $penalty;
            }
}
