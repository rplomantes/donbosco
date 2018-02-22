<?php

namespace App\Http\Controllers\Accounting\Assesment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MakePaymentSchedule extends Controller
{
    function index(){
        
    }
    
    function makeschedule(Request $request){
        $level = $request->level;
        $course = $request->course;
        $plan = $request->plan;
        
        if(in_array($level,array('Grade 11','Grade 12'))){
            \App\CtrPaymentSchedule::where('plan',$plan)->where('level',$level)->where('course',$course)->delete();
        }elseif(in_array($level,array('Grade 9','Grade 10'))){
            \App\CtrPaymentSchedule::where('plan',$plan)->where('level',$level)->where('strand',$course)->delete();
        }else{
            \App\CtrPaymentSchedule::where('plan',$plan)->where('level',$level)->delete();
        }
        
        $accounts = \App\CtrAssessmentAccount::where('level',$level)->where('course_strand',$course)->get();
        foreach($accounts as $account){
            $breakable = \App\CtrBreakableAccount::where('plan',$plan)->where('accountingcode',$account->accountingcode)->exists();
            
            if($breakable){
                $this->processBreakable($request,$account);
            }else{
                $this->writeRecord($request,$account, $account->amount, '0000-00-00',0, 1);
            }
        }
        
        if($request->level != "Kindergarten"){
            $this->processDiscount($request);
        }
        return redirect()->back();
    }
    
    function processDiscount($request){
        $level = $request->level;
        $course = $request->course;
        $plan = $request->plan;
        $discount = 0;
        
        switch ($plan){
            case "Annual":
                $discount = .03;
                break;
            case "Semi Annual":
                $discount = .015;
                break;
        }
        if($discount > 0){
            $paymentSched = \App\CtrPaymentSchedule::where('plan',$plan)->where('level',$level)->where('accountingcode',120100)->get();
            
            
            $accountsWithDisc = $paymentSched->where('duetype',0,false);
            $total_discount = round($paymentSched->sum('amount')*$discount,2);
            $disc_perAcct = round($total_discount/count($accountsWithDisc),2);
            foreach($accountsWithDisc as $account){
                $account->discount = $disc_perAcct;
                $account->save();
            }
            
        }
        
        
        
    }
    
    function processBreakable($request,$account){
        $level = $request->level;
        $course = $request->course;
        $plan = $request->plan;
        
        $totalmonth = 10;
        $currentmonth = 1;
        $amount = 0;
        
        
        $schedules = \App\CtrRefInstallment::where('plan',$plan)->get();
        
        foreach($schedules as $schedule){
            $covered_month = 0;
            $duedate = $schedule->duedate;
            $duetype = 1;
            
            if($duedate == "0000-00-00"){
                $duetype = 0;
            }
            
            while($covered_month < $schedule->covered_month){
                $amount = round($account->amount / 10,2);
                $this->writeRecord($request, $account, $amount, $duedate, $duetype, $currentmonth);
                $covered_month++;
                $currentmonth++;
            }
        }
        
    }
    
    function writeRecord($request,$account,$amount,$duedate,$duetype,$month){
                        $department = $this->get_department($account->level);
                        $schoolyear = 2018;
                        
                        $newsched = new \App\CtrPaymentSchedule;
                        $newsched->plan = $request->plan;
                        $newsched->department = $department;
                        $newsched->level = $account->level;
                        if($department == "Junior High School"){
                            $newsched->strand = $account->course_strand;
                        }else{
                            $newsched->course = $account->course_strand;
                        }
                        
                        $newsched->categoryswitch = $this->get_categorySwitch($account->accountingcode);
                        $newsched->accountingcode = $account->accountingcode;
                        $newsched->acctcode = $account->accountname;
                        $newsched->description = $account->description;
                        $newsched->receipt_details = $account->receiptdetails;
                        $newsched->amount= $amount;
                        $newsched->acct_department=$account->main_department;
                        $newsched->sub_department=$account->sub_department;;
                        $newsched->schoolyear = $schoolyear;
                        $newsched->duetype = $duetype;
                        $newsched->month = $month;
                        $newsched->duedate = $duedate;
                        $newsched->save();
    }
    
    function get_department($level){
        $department = "";
        $levels = \App\CtrLevel::where('level',$level)->first();
        if($levels){
            $department = $levels->department;
        }
        
        return $department;
    }
    
    function get_categorySwitch($accountcode){
        switch($accountcode){
            case "420200":
                return 1;
            case "420400":
                return 2;
            case "420100":
                return 4;
            case "420000":
                return 5;
            case "120100":
                return 6;
            default :
                return 0;
        }
    }
}
