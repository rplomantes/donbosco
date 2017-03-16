<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class AddtoAccountController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function addtoaccount($studentid){
        $accounts = \App\CtrOtherPayment::orderBy('particular')->get();
        $studentdetails = \App\User::where('idno',$studentid)->first();
        $statuses = \App\Status::where('idno',$studentid)->first();
        $acct_departments = DB::Select('Select * from ctr_acct_dept order by sub_department');
        $deletes = DB::Select("Select * from deleted_accounts where idno='$studentid' AND categoryswitch = '7'");
        $ledgers = DB::Select("Select * from ledgers where idno='$studentid' AND categoryswitch = '7' and amount > payment ");
        return view('cashier.addtoaccount',compact('studentid','accounts','studentdetails','statuses','ledgers','deletes','acct_departments'));
        
    }
    function posttoaccount(Request $request){
        $this->savetoaccount($request->idno,$request->all());
        return redirect('addtoaccount/'.$request->idno);
    }
        
    static function savetoaccount($idno,array $request){
        $status = \App\Status::where('idno',$idno)->first();
        $newledger = new \App\Ledger;
        $acctcode = \App\ChartOfAccount::where('acctcode',$request['accttype'])->first();
        
        if(count($status)>0){
        $newledger->level=$status->level;
        $newledger->course=$status->course;
        $newledger->strand=$status->strand;
        $newledger->department = $status->department;
        $newledger->schoolyear=$status->schoolyear;
        $newledger->period=$status->period;
        }
        
        $newledger->idno = $idno;
        $newledger->transactiondate = Carbon::now();
        $newledger->categoryswitch = '7';
        $newledger->accountingcode=$acctcode->acctcode;
        $newledger->acctcode=$acctcode->accountname;
        
        $newledger->postedby=\Auth::user()->idno;
        if(isset($request['particular']) && ($request['particular'] != "" || $request['particular'] != NULL)){
            $newledger->description=$request['particular'];
            $newledger->receipt_details=$request['particular'];
        }else{
            $newledger->description=$acctcode->accountname;
            $newledger->receipt_details=$acctcode->accountname;
        }
        $newledger->duetype="0";
        $newledger->duedate=Carbon::now();
        $newledger->amount=$request['amount'];
        $newledger->sub_department=$request['department'];
        $newledger->remark=$request['remark'];
        $newledger->save();
        //return $this->addtoaccount($request['idno']);
        //return redirect(url('addtoaccount',$request->idno));
    }
    
    function addtoaccountdelete($id){
        $account = \App\Ledger::where('id',$id)->first();
        if($account->payment+$account->debitmemo==0){
            
            $deleteAccount = new \App\DeletedAccount;
            $deleteAccount->level=$account->level;
            $deleteAccount->course=$account->course;
            $deleteAccount->strand=$account->strand;
            $deleteAccount->department = $account->department;
            $deleteAccount->schoolyear=$account->schoolyear;
            $deleteAccount->period=$account->period;
            $deleteAccount->idno = $account->idno;
            $deleteAccount->categoryswitch = $account->categoryswitch;
            $deleteAccount->transactiondate = $account->transactiondate;
            $deleteAccount->acctcode=$account->acctcode;
            $deleteAccount->description=$account->description;
            $deleteAccount->postedby=$account->postedby;
            $deleteAccount->receipt_details=$account->receipt_details;
            $deleteAccount->duetype=$account->duetype;
            $deleteAccount->duedate=$account->duedate;
            $deleteAccount->amount=$account->amount;
            $deleteAccount->remark=$account->remark;
            
            $deleteAccount->deleted=Input::get('remark');
            $deleteAccount->save();
            
            $account->delete();  
        }
        return $this->addtoaccount($account->idno);
        //return redirect(url('addtoaccount',$account->idno));
    }
}
