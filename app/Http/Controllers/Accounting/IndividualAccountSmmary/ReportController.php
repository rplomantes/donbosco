<?php

namespace App\Http\Controllers\Accounting\IndividualAccountSmmary;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Http\Controllers\Accounting\Disbursement\Helper as DisbHelper;
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

class ReportController extends Controller
{
    function index(){
        $request = new Request();
        return $this->callView($request);
    }
    
    function callView($request){
        return view('accounting.IndividualAccountSummary.index',compact('request'));
    }
    
    
    function submitRequest(Request $request){
        return $this->callView($request);
    }
    
    static function renderForm($request){
        $accounts = \App\ChartOfAccount::all();
        $departments = \App\CtrAcctDep::all();
        return view('accounting.IndividualAccountSummary.form',compact('accounts','departments','request'));
    }
    
    static function renderResult($request){
        $grouping = $request->input('group');
        
        $viewaccounts = self::accounts($request->from,$request->to,$request->account,$grouping)->sortBy('transactiondate')->sortBy('created_at');
        
        if($request->input('department') != ""){
            $viewaccounts = $viewaccounts->where('acctdepartment',$request->input('department'),false);
        }
        
        if($request->input('office') != ""){
            $viewaccounts = $viewaccounts->where('subdepartment',$request->input('office'),false);
        }
        
        if($request->input('type') != ""){
            $viewaccounts = $viewaccounts->where('entry_type',$request->input('type'),false);
        }
        
        if($request->input('remarks') != ""){
            $remark = $request->input('remarks');
            $viewaccounts = $viewaccounts->filter(function($items)use($remark){
                return stristr($items->particular, $remark);
            });
        }
        session()->put('iasVar',$request->all());
        session()->put('iasAccount',$viewaccounts);
        
        if($grouping == ""){
            return view('accounting.IndividualAccountSummary.resultNonGroup',compact('viewaccounts'));
        }else{
            return view('accounting.IndividualAccountSummary.resultNonGroup',compact('viewaccounts'));
        }
        
    }
    
    function printAccount(Request $request){
        ini_set('memory_limit', '-1');
        
        $formVar = session()->get('iasVar');
        $from = $formVar['from'];
        $to = $formVar['to'];
        $account = $formVar['account'];
        $accounts = session()->get('iasAccount');
        
        $title = 'INDIVIDUAL ACCOUNT SUMMARY <br>'.\App\ChartOfAccount::where('acctcode',$account)->first()->accountname;
        
        $pdf= \App::make('dompdf.wrapper');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView('accounting.IndividualAccountSummary.print.printNonGroup',compact('title','from','to','account','accounts','request'));
        return $pdf->stream();
    }

    static function accounts($from,$to,$account,$grouping){
        if($grouping == ""){
            $groupBy = array('refno','acct_department','sub_department');
        }else{
            $groupBy = array('refno','acct_department','sub_department','description');
        }
            
        $debits = self::debits($from, $to, $account,$groupBy);
        $credits = self::credits($from, $to, $account,$groupBy);
        
        return $debits->merge($credits);
        
    }
    
    static function debits($from,$to,$account,$groupBy){
        $accounts = \App\Dedit::selectRaw('*,sum(amount) as totalamount,sum(checkamount) as totalcheck')->whereBetween('transactiondate',[$from,$to])->where('accountingcode',$account)->where('isreverse',0)->groupBy($groupBy)->get();
        $debits = array();
        
        foreach($accounts as $acct){
            $level = Info::get_level($acct->idno, $acct->schoolyear);
            $section = Info::get_section($acct->idno, $acct->schoolyear);
            if($acct->entry_type == 4){
                $payee = DisbHelper::get_payee($acct->refno);
                $remarks = DisbHelper::get_remarks($acct->refno);
            }else{
                $payee = $acct->receivefrom;
                $remarks = $acct->remarks;
            }
            
            $debits[] = (object)['schoolyear'=>$acct->schoolyear,'refno'=>$acct->refno,'idno'=>$acct->idno,'entry_type'=>$acct->entry_type,'created_at'=>$acct->created_at,'transactiondate'=>$acct->transactiondate,'receiptno'=>$acct->receiptno,
                'payee'=>$payee,'debit'=>$acct->totalamount+$acct->totalcheck,'credit'=>0,'acctdepartment'=>$acct->acct_department,'subdepartment'=>$acct->sub_department,'particular'=>$remarks,'subaccount'=>$acct->descripiton,'level'=>$level,'section'=>$section];
        }
        
        return collect((object)$debits);
    }
    
    static function credits($from,$to,$account,$groupBy){
        $accounts =  \App\Credit::selectRaw('*,sum(amount) as totalamount')->with('Dedit')->whereBetween('transactiondate',[$from,$to])->where('accountingcode',$account)->where('isreverse',0)->groupBy($groupBy)->get();
        $credits = array();
        
        foreach($accounts as $acct){
            $level = Info::get_level($acct->idno, $acct->schoolyear);
            $section = Info::get_section($acct->idno, $acct->schoolyear);
            if($acct->entry_type == 4){
                $payee = DisbHelper::get_payee($acct->refno);
                $remarks = DisbHelper::get_remarks($acct->refno);
            }else{
                $payee = $acct->dedit->pluck('receivefrom')->last();
                $remarks = $acct->dedit->pluck('remarks')->last();
            }
            
            $credits[] = (object)['schoolyear'=>$acct->schoolyear,'refno'=>$acct->refno,'idno'=>$acct->idno,'entry_type'=>$acct->entry_type,'created_at'=>$acct->created_at,'transactiondate'=>$acct->transactiondate,'receiptno'=>$acct->receiptno,
                'payee'=>$payee,'debit'=>0,'credit'=>$acct->totalamount,'acctdepartment'=>$acct->acct_department,'subdepartment'=>$acct->sub_department,'particular'=>$remarks,'subaccount'=>$acct->descripiton,'level'=>$level,'section'=>$section];
        }
        
        return collect((object)$credits);
    }
}
