<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class CashierController extends \App\Http\Controllers\Cashier\CashierController
{
    public function __construct(){
        $this->middleware('auth');
    }

    function searchor(){
        if((\Auth::user()->accesslevel==env('USER_CASHIER'))||(\Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD'))){
            return view('vincent.cashier.searchor');
        }
    }
    
    function findor(request $request){
        if((\Auth::user()->accesslevel==env('USER_CASHIER'))||(\Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD'))){
            $search = \App\Dedit::where('receiptno',$request->or)->where('isreverse',0)->first();
            if(empty($search)){
                $noOR = $request->or;
                return view('vincent.cashier.searchor',compact('noOR'));
            }
            return $this->viewreceipt($search->refno,$search->idno);
        }
    }    

    function batchposting(){
        if(\Auth::user()->accesslevel==env('USER_CASHIER')){
        $acctcode = \App\CtrOtherPayment::distinct()->select('accounttype')->orderBy('accounttype')->get();
        
        return view('vincent.cashier.addbatchaccount',compact('acctcode'));
        }
    }
    
    function savebatchposting(Request $request){
        if(\Auth::user()->accesslevel==env('USER_CASHIER')){
        foreach($request->idnumber as $id){
            $status = \App\Status::where('idno',$id)->first();
            $newledger = new \App\Ledger;
            $acctcode = \App\CtrOtherPayment::where('accounttype',$request->accttype)->where('particular',$request->subsidy)->first();
            $myacct=$acctcode->accounttype;
            if(count($status)>0){
            $newledger->level=$status->level;
            $newledger->course=$status->course;
            $newledger->strand=$status->strand;
            $newledger->department = $status->department;
            $newledger->schoolyear=$status->schoolyear;
            $newledger->period=$status->period;
            }
            $newledger->idno = $id;
            $newledger->categoryswitch = '7';
            $newledger->transactiondate = Carbon::now();       
            $newledger->acctcode=$myacct;
            $newledger->description=$request->subsidy;
            $newledger->postedby=\Auth::user()->idno;
            $newledger->receipt_details=$request->subsidy;
            $newledger->duetype="0";
            $newledger->duedate=Carbon::now();
            $newledger->amount=$request->amount;
            $newledger->save();            
        }

        $students = DB::Select("Select distinct statuses.idno,lastname,firstname,middlename,extensionname,statuses.section from statuses join users on users.idno = statuses.idno join ctr_sections on ctr_sections.section = statuses.section where statuses.idno IN(".implode(',',$request->idnumber).") order by ctr_sections.id,lastname,firstname,middlename,extensionname");
        
        return view('vincent.cashier.studentwithacct',compact('students','request'));
        }
    }
}
