<?php

namespace App\Http\Controllers\Accounting\Assesment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Accounting\Helper;

class AccountBreakdown extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }
    function module_info(){
        return ['menu'=>1,'title'=>'Levels Account Breakdown'];
    }
    
    function index(){
        $levels = \App\CtrLevel::all();
        $module_info = $this->module_info();
        
        return view("accounting.EnrollmentAssessment.chooseLevelStrand",compact('levels','module_info'));
    }
    function create($level,$course = ""){
        $assessments = $this->get_assessment($level,$course);
        $coa = \App\ChartOfAccount::pluck('accountname')->toArray();
        $module_info = $this->module_info();
        return view("accounting.EnrollmentAssessment.accountsBreakdown.createbreakdown",compact('assessments','coa','level','course','module_info'));
    }
    
    function get_assessment($level,$course){
        $assessment = \App\CtrAssessmentAccount::get();
        if(count($assessment)>0){
            $levelassessment = $assessment->where('level',$level,false)->where('course_strand',$course,false);
            if(count($levelassessment)>0){
                return $levelassessment;
            }else{
                
                return $assessment->where('level',$assessment->pluck('level')->last(),false)->where('course_strand',$assessment->pluck('course_strand')->last(),false);
            }
        }else{
            return $assessment;
        }
    }
    
    function saveAssessment(Request $request){
        $subaccounts = $request->subacct;
        $level = $request->level;
        $course = $request->course;
        \App\CtrAssessmentAccount::where('level',$level)->where('course_strand',$course)->delete();
        foreach($subaccounts as $key=>$acctgroup){
            $group = $key;
            $acctcode = $request->input('acctcode.'.$group);
            $acctname = $request->input('acct.'.$group);
            
            foreach($acctgroup as $key=>$subaccount){
                $row = $key;
                $subsidiary = $subaccount;
                $amount = $request->input('amount.'.$group.'.'.$row);
                $office = $request->input('office.'.$group.'.'.$row);
                
                if(!($subsidiary == "" || $subsidiary == NULL)){
                    $this->addAccount($level,$course, $acctcode, $acctname, $subsidiary, $office, $amount);
                }
                
            }
        }
        
        return redirect()->back();
    }
    
    function addAccount($level,$course,$acctcode,$acctname,$subsidiary,$office,$amount){
        $account = new \App\CtrAssessmentAccount();
        $account->level = $level;
        $account->course_strand = $course;
        $account->accountingcode = $acctcode;
        $account->accountname = $acctname;
        $account->description = $subsidiary;
        $account->receiptdetails = $acctname;
        $account->main_department = Helper::get_Department($office);
        $account->sub_department = $office;
        $account->amount = $amount;
        $account->save();
        
        
        
        
    }
}
