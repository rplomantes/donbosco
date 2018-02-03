<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Excel;
class CashReceiptController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function cashreceiptbook($transactiondate){
        $rangedate = date("Y-m",strtotime($transactiondate));
        
        $debits = \App\Dedit::whereBetween('transactiondate', array($rangedate."-01",$transactiondate))->where('entry_type',1)->orderBy('receiptno')->get();
        $credits = \App\Credit::whereBetween('transactiondate', array($rangedate."-01",$transactiondate))->where('entry_type',1)->get();
        
        $receipts = $debits->where('transactiondate',$transactiondate)->unique('refno');
        
        $this->processbook($receipts,$debits,$credits);
        $this->forwarded($transactiondate,$debits,$credits);
        
        $records = \App\RptCashreceiptBook::where('idno',\Auth::user()->idno)->get();
        
        return view('accounting.CashReceipt.Book.book',compact('transactiondate','records','credits','debits'));
    }
    
    function cashreceiptpdf($transactiondate){
        $rangedate = date("Y-m",strtotime($transactiondate));
        
        $records = \App\RptCashreceiptBook::where('idno',\Auth::user()->idno)->get();
        $credits = \App\Credit::whereBetween('transactiondate', array($rangedate."-01",$transactiondate))->where('entry_type',1)->get();
        $title = 'CASH RECEIPT BOOK';
        
        $receipts = $records->where('transactiondate',$transactiondate);
        $group_count = 21;
        $chunk = $this->customChunk($receipts,$group_count);
        
        $pdf = \App::make('dompdf.wrapper');
        //$pdf->setPaper([0,0,612.00,936.00],'landscape');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView('accounting.CashReceipt.Book.printbook',compact('transactiondate','records','credits','title','receipts','chunk','group_count'));
        return $pdf->stream();

    }
    
    function cashreceiptexcel($transactiondate){
        $rangedate = date("Y-m",strtotime($transactiondate));
        
        $debits = \App\Dedit::whereBetween('transactiondate', array($rangedate."-01",$transactiondate))->where('entry_type',1)->get();
        $credits = \App\Credit::whereBetween('transactiondate', array($rangedate."-01",$transactiondate))->where('entry_type',1)->get();
        
        $records = \App\RptCashreceiptBook::where('idno',\Auth::user()->idno)->get();
        
        $name = "Cash Receipt Books_".$transactiondate;
        
        Excel::create($name, function($excel) use($records,$transactiondate,$credits,$debits) {
            $excel->sheet('Book', function($sheet) use($records,$transactiondate){
                    $sheet->loadView('accounting.CashReceipt.Book.downloadbook')->with('records',$records)->with('transactiondate',$transactiondate)
                            ->setFontSize(10)
                            ->setColumnFormat(array(
                                'C'=> '#,##0.00',
                                'D'=> '#,##0.00',
                                'E'=> '#,##0.00',
                                'F'=> '#,##0.00',
                                'G'=> '#,##0.00',
                                'H'=> '#,##0.00',
                                'I'=> '#,##0.00',
                                'J'=> '#,##0.00',
                                'K'=> '#,##0.00',
                                'L'=> '#,##0.00',
                                'M'=> '#,##0.00',
                            ));
            });
            
            $excel->sheet('Sundries', function($sheet) use($credits,$transactiondate,$debits){
                    $sheet->loadView('accounting.CashReceipt.Book.downloadsundries')->with('credits',$credits)->with('debits',$debits)->with('transactiondate',$transactiondate)
                            ->setFontSize(10)
                            ->setColumnFormat(array(
                                'B'=> '#,##0.00',
                            ));
            });
        })->export('xlsx');
        

    }
    
    function processbook($receipts,$debits,$credits){
        ini_set('max_execution_time', 0);
        \App\RptCashreceiptBook::where('idno',\Auth::user()->idno)->delete();
        
        foreach($receipts as $receipt){
            $credit = $credits->where('refno',$receipt->refno);
            $debit  = $debits->where('refno',$receipt->refno);
                $reportEntry = new \App\RptCashreceiptBook();
                $reportEntry->idno = \Auth::user()->idno;
                $reportEntry->from = $receipt->receivefrom;
                $reportEntry->refno = $receipt->refno;
                $reportEntry->transactiondate = $receipt->transactiondate;
                $reportEntry->receiptno = $receipt->receiptno;
                $reportEntry->cash = $debit->where('paymenttype','1')->sum('amount')+$debit->where('paymenttype',1)->sum('checkamount');
                $reportEntry->discount = $debit->where('paymenttype','4')->sum('amount');
                $reportEntry->fape = $debit->where('paymenttype','7')->sum('amount');
                $reportEntry->dreservation = $debit->where('paymenttype','5')->sum('amount');
                $reportEntry->deposit = $debit->where('paymenttype','8')->sum('amount');
                
                $debitSundries = $debit->filter(function($item){
                                                return !in_array(data_get($item, 'paymenttype'), array('1','4'));
                                                    });
                $debit_accts = $this->creditSundries($debitSundries);
                                                    
                $reportEntry->dsundry = $debitSundries->sum('amount');
                $reportEntry->dsundry_account = $debit_accts['accounts'];
                $debit_row = $debit_accts['rows'];
                                                    
                $reportEntry->elearning = $credit->where('accountingcode',420200)->sum('amount');
                $reportEntry->misc = $credit->where('accountingcode',420400)->sum('amount');
                $reportEntry->book = $credit->where('accountingcode',440400)->sum('amount');
                $reportEntry->dept = $credit->where('accountingcode',420100)->sum('amount');
                $reportEntry->registration = $credit->where('accountingcode',420000)->sum('amount');
                $reportEntry->tuition = $credit->whereIn('accountingcode',array(120100,410000))->sum('amount');
                $reportEntry->creservation = $credit->where('accountingcode',210400)->sum('amount');
                
                $creditSundries = $credit->filter(function($item){
                                                return !in_array(data_get($item, 'accountingcode'), array(420200,420400,440400,420100,420000,120100,410000,210400));
                                                    });               
                $credit_accts = $this->creditSundries($creditSundries);
                
                $reportEntry->csundry = $creditSundries->sum('amount');                                    
                $reportEntry->csundry_account = $credit_accts['accounts'];
                $credit_row = $credit_accts['rows'];
                
                if($credit_row > $debit_row){
                    $reportEntry->row_count = $credit_row;
                }else{
                    $reportEntry->row_count = $debit_row;
                }
                
                $reportEntry->isreverse = $receipt->isreverse;
                $reportEntry->save();
                                                    
        }
    }
    
    function creditSundries($credit){
        $credit_group = $credit->groupBy('accountingcode');
        $bd_credits = "";
        $rowcount = 1;
        foreach($credit_group as $credits){
            $bd_credits = $bd_credits.$credits->pluck('acctcode')->last()." - ".number_format($credits->sum('amount'),2)."<br>";   
        }
        
        if(count($credit_group)>0){
            $rowcount = count($credit_group);
        }
        return array('accounts'=>$bd_credits,'rows'=>$rowcount);
    }
    
    function debitSundries($debit){
        $debit_group = $debit->groupBy('accountingcode');
        $bd_debits = "";
        $rowcount = 1;
        foreach($debit_group as $debits){
                $bd_debits = $bd_debits.$debits->pluck('acctcode')->last()." - ".number_format($debits->sum('amount'),2)."<br>";
        }
        
        if(count($debit_group)>0){
            $rowcount = count($debit_group);
        }
        
        return array('accounts'=>$bd_debits,'rows'=>$rowcount);
    }
    
    function forwarded($transactiondate,$debits,$credits){
            $credit = $credits->where('isreverse',0)->filter(function($item) use($transactiondate){
                return data_get($item, 'transactiondate') !== $transactiondate;
                });
            $debit  = $debits->where('isreverse',0)->filter(function($item) use($transactiondate){
                return data_get($item, 'transactiondate') !== $transactiondate;
                });
                
                $reportEntry = new \App\RptCashreceiptBook();
                $reportEntry->idno = \Auth::user()->idno;
                $reportEntry->refno = "forwarded";
                $reportEntry->cash = $debit->where('paymenttype','1')->sum('amount')+$debit->where('paymenttype',1)->sum('checkamount');
                $reportEntry->discount = $debit->where('paymenttype','4')->sum('amount');
                $reportEntry->dsundry = $debit->filter(function($item){
                                                return !in_array(data_get($item, 'paymenttype'), array('1','4'));
                                                    });
                
                $reportEntry->elearning = $credit->where('accountingcode',420200)->sum('amount');
                $reportEntry->misc = $credit->where('accountingcode',420400)->sum('amount');
                $reportEntry->book = $credit->where('accountingcode',440400)->sum('amount');
                $reportEntry->dept = $credit->where('accountingcode',420100)->sum('amount');
                $reportEntry->registration = $credit->where('accountingcode',420000)->sum('amount');
                $reportEntry->tuition = $credit->whereIn('accountingcode',array(120100,410000))->sum('amount');
                $reportEntry->creservation = $credit->where('accountingcode',210400)->sum('amount');
                $reportEntry->csundry = $credit->filter(function($item){
                                                return !in_array(data_get($item, 'accountingcode'), array(420200,420400,440400,420100,420000,120100,410000,210400));
                                                    })->sum('amount');
                $reportEntry->save();
    }
    
    function customChunk($records,$size){
        $chunks = array();
        $currntrows = 0;
        $group = array();
        
        foreach($records  as $items){
            if($size < $currntrows+$items->row_count || ($items ==$records->last())){
               $chunks[] = collect($group);
               unset($group);
               $currntrows = 0;
            }
            
            $currntrows = $currntrows+$items->row_count;
            $group[] = $items;
        }
        
        return collect($chunks);
    }


}
