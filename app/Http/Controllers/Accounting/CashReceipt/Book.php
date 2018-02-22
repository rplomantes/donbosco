<?php

namespace App\Http\Controllers\Accounting\CashReceipt;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Book extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function cashreceiptbook($transactiondate){
        $rangedate = date("Y-m",strtotime($transactiondate));
        
        $debits  =  \App\Dedit::whereBetween('transactiondate', array($rangedate."-01",$transactiondate))->where('entry_type',1)->orderBy('receiptno')->get();
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
        \App\RptCashReceiptBookSundries::where('idno',\Auth::user()->idno)->delete();
        
        foreach($receipts as $receipt){
            $credit = $credits->where('refno',$receipt->refno);
            $debit  = $debits->where('refno',$receipt->refno);
                $reportEntry = new \App\RptCashreceiptBook();
                $reportEntry->idno = \Auth::user()->idno;
                $reportEntry->from = $receipt->receivefrom;
                $reportEntry->refno = $receipt->refno;
                $reportEntry->transactiondate = $receipt->transactiondate;
                $reportEntry->receiptno = $receipt->receiptno;
                $reportEntry->cash = $debit->where('paymenttype',1,false)->sum('amount')+$debit->where('paymenttype',1,false)->sum('checkamount');
                $reportEntry->discount = $debit->where('paymenttype',4,false)->sum('amount');
                
                $debitSundries = $debit->filter(function($item){
                                                return !in_array(data_get($item, 'paymenttype'), array('1','4'));
                                                    });
                                                    
                $reportEntry->dsundry = $debitSundries->sum('amount');
                $debit_row = $this->processSundries($debitSundries,'debit');
                                                    
                $reportEntry->elearning = $credit->where('accountingcode',420200,false)->sum('amount');
                $reportEntry->misc = $credit->where('accountingcode',420400,false)->sum('amount');
                $reportEntry->book = $credit->where('accountingcode',440400,false)->sum('amount');
                $reportEntry->dept = $credit->where('accountingcode',420100,false)->sum('amount');
                $reportEntry->registration = $credit->where('accountingcode',420000,false)->sum('amount');
                $reportEntry->tuition = $credit->whereIn('accountingcode',array(120100,410000))->sum('amount');
                $reportEntry->creservation = $credit->where('accountingcode',210400,false)->sum('amount');
                
                $creditSundries = $credit->filter(function($item){
                                                return !in_array(data_get($item, 'accountingcode'), array(420200,420400,440400,420100,420000,120100,410000,210400));
                                                    });               
                
                $reportEntry->csundry = $creditSundries->sum('amount');                                    
                $credit_row = $this->processSundries($creditSundries,'credit');
                
                
                $reportEntry->row_count = ($credit_row+$debit_row)-1;
                
                $reportEntry->isreverse = $receipt->isreverse;
                $reportEntry->save();
                                                    
        }
    }
    
    function processSundries($accounts,$table){
           
           $entries = $accounts->groupBy("accountingcode");
           $rowcount = $entries->count();
           foreach($entries as $entry){
               $debit = 0;
               $credit = 0;
               $sundries = \App\RptCashReceiptBookSundries::where('idno',\Auth::user()->idno)
                       ->where('refno',$entry->pluck('refno')->last())
                       ->where('accountingcode',$entry->pluck('accountcode')->last())->first();
               if(!$sundries){
                    $sundries = new \App\RptCashReceiptBookSundries();
                    $sundries->idno = \Auth::user()->idno;
                    $sundries->refno = $entry->pluck('refno')->last();
                    $sundries->particular = $entry->pluck('acctcode')->last();
                    $sundries->accountingcode = $entry->pluck('accountingcode')->last();
               }
               
               if($table == 'credit'){
                   $sundries->credit = $entry->sum('amount');
               }else{
                   $sundries->debit = $entry->sum('amount');
               }
               
               $sundries->save();

           }
           
           if($rowcount > 0){
               return $rowcount;
           }else{
               return 1;
           }
    }
    
    
    function forwarded($transactiondate,$debits,$credits){
            $credit = $credits->where('isreverse',0,false)->filter(function($item) use($transactiondate){
                return data_get($item, 'transactiondate') !== $transactiondate;
                });
            $debit  = $debits->where('isreverse',0,false)->filter(function($item) use($transactiondate){
                return data_get($item, 'transactiondate') !== $transactiondate;
                });
                
                $reportEntry = new \App\RptCashreceiptBook();
                $reportEntry->idno = \Auth::user()->idno;
                $reportEntry->refno = "forwarded";
                $reportEntry->cash = $debit->where('paymenttype',1,false)->sum('amount')+$debit->where('paymenttype',1,false)->sum('checkamount');
                $reportEntry->discount = $debit->where('paymenttype',4,false)->sum('amount');
                $reportEntry->dsundry = $debit->filter(function($item){
                                                return !in_array(data_get($item, 'paymenttype'), array('1','4'));
                                                    });
                
                $reportEntry->elearning = $credit->where('accountingcode',420200,false)->sum('amount');
                $reportEntry->misc = $credit->where('accountingcode',420400,false)->sum('amount');
                $reportEntry->book = $credit->where('accountingcode',440400,false)->sum('amount');
                $reportEntry->dept = $credit->where('accountingcode',420100,false)->sum('amount');
                $reportEntry->registration = $credit->where('accountingcode',420000,false)->sum('amount');
                $reportEntry->tuition = $credit->whereIn('accountingcode',array(120100,410000),false)->sum('amount');
                $reportEntry->creservation = $credit->where('accountingcode',210400,false)->sum('amount');
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
