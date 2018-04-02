<?php

namespace App\Http\Controllers\Accounting\IndividualAccountSmmary;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;


use App\Http\Controllers\Accounting\IndividualAccountSmmary\Helper as IASHelper;

class ReportController extends Controller
{
    function __construct(){
        $this->middleware(['auth','accounting']);
    }
    function index(){
        $request = new Request();
        $request->replace(['from'=>Carbon::now()->format('Y-m-d'),'to'=>Carbon::now()->format('Y-m-d')]);
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
        return $request->account;
        $grouping = $request->input('grouping');
        
        $viewaccounts = IASHelper::accounts($request->from,$request->to,$request->account,$grouping)->sortBy('transactiondate')->sortBy('created_at');
        
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
        }elseif($grouping == "byDepartment"){
            return view('accounting.IndividualAccountSummary.resultDepartmentGroup',compact('viewaccounts'));
        }else{
            //return $viewaccounts->groupBy('subaccount');
            return view('accounting.IndividualAccountSummary.resultSbAccountGroup',compact('viewaccounts'));
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

    function printSubAccount(Request $request,$subsidiary){
        ini_set('memory_limit', '-1');
        
        $formVar = session()->get('iasVar');
        $from = $formVar['from'];
        $to = $formVar['to'];
        $account = $formVar['account'];
        $accounts = session()->get('iasAccount')->where('subaccount',$subsidiary,false);
        
        $title = 'INDIVIDUAL ACCOUNT SUMMARY <br>'.\App\ChartOfAccount::where('acctcode',$account)->first()->accountname." - ".$subsidiary;
        
        $pdf= \App::make('dompdf.wrapper');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView('accounting.IndividualAccountSummary.print.printNonGroup',compact('title','from','to','account','accounts','request'));
        return $pdf->stream();
    }
    
    function printDepartment(Request $request,$department){
        ini_set('memory_limit', '-1');
        
        $formVar = session()->get('iasVar');
        $from = $formVar['from'];
        $to = $formVar['to'];
        $account = $formVar['account'];
        $accounts = session()->get('iasAccount')->where('acctdepartment',$department,false);
        
        $title = 'INDIVIDUAL ACCOUNT SUMMARY <br>'.\App\ChartOfAccount::where('acctcode',$account)->first()->accountname." - ".$department;
        
        $pdf= \App::make('dompdf.wrapper');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView('accounting.IndividualAccountSummary.print.printNonGroup',compact('title','from','to','account','accounts','request'));
        return $pdf->stream();
    }
    
    function downloadAccount(Request $request){
        ini_set('memory_limit', '-1');
        
        $formVar = session()->get('iasVar');
        $from = $formVar['from'];
        $to = $formVar['to'];
        $account = $formVar['account'];
        $accounts = session()->get('iasAccount');
        $name = "Accounts Summary for ".$account." from".$from." to ".$to;
        //return $request->input('date');
        Excel::create($name,function($excel)use($accounts,$request){
            $excel->sheet('Sheet1', function($sheet) use($accounts,$request){
                $sheet->loadView('accounting.IndividualAccountSummary.download.dlNonGroup')
                        ->with('accounts',$accounts)
                        ->with('request',$request)
                        ->setFontSize(10)
                        ->setAutoSize(true);
                
                $sheet->setColumnFormat(array(
                            'F'=> '#,##0.00',
                            'G'=> '#,##0.00'
                        ));
            });
            
        })->export('xlsx');
    }
    
    function downloadSubAccount(Request $request,$subsidiary){
        ini_set('memory_limit', '-1');
        
        $formVar = session()->get('iasVar');
        $from = $formVar['from'];
        $to = $formVar['to'];
        $account = $formVar['account'];
        $accounts = session()->get('iasAccount')->where('subaccount',$subsidiary,false);;
        $name = "Accounts Summary for ".$subsidiary." from".$from." to ".$to;
        //return $request->input('date');
        Excel::create($name,function($excel)use($accounts,$request){
            $excel->sheet('Sheet1', function($sheet) use($accounts,$request){
                $sheet->loadView('accounting.IndividualAccountSummary.download.dlNonGroup')
                        ->with('accounts',$accounts)
                        ->with('request',$request)
                        ->setFontSize(10)
                        ->setAutoSize(true);
                
                $sheet->setColumnFormat(array(
                            'F'=> '#,##0.00',
                            'G'=> '#,##0.00'
                        ));
            });
            
        })->export('xlsx');
    }
    
    function downloadDepartment(Request $request,$department){
        ini_set('memory_limit', '-1');
        
        $formVar = session()->get('iasVar');
        $from = $formVar['from'];
        $to = $formVar['to'];
        $account = $formVar['account'];
        $accounts = session()->get('iasAccount')->where('acctdepartment',$department,false);
        $name = "Accounts Summary of ".$department." from".$from." to ".$to;
        //return $request->input('date');
        Excel::create($name,function($excel)use($accounts,$request){
            $excel->sheet('Sheet1', function($sheet) use($accounts,$request){
                $sheet->loadView('accounting.IndividualAccountSummary.download.dlNonGroup')
                        ->with('accounts',$accounts)
                        ->with('request',$request)
                        ->setFontSize(10)
                        ->setAutoSize(true);
                
                $sheet->setColumnFormat(array(
                            'F'=> '#,##0.00',
                            'G'=> '#,##0.00'
                        ));
            });
            
        })->export('xlsx');
    }
}
