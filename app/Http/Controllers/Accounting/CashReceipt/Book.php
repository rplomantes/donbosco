<?php

namespace App\Http\Controllers\Accounting\CashReceipt;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class Book extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function cashreceiptbook($transactiondate){
        $rangedate = date("Y-m",strtotime($transactiondate));
        
        $debits  =  \App\Dedit::select(DB::raw('idno,transactiondate,receiptno,refno,accountingcode,acctcode,amount,isreverse,checkamount,receivefrom,paymenttype'))->whereBetween('transactiondate', array($rangedate."-01",$transactiondate))->where('entry_type',1)->orderBy('receiptno')->get();
        $receipts = $debits->where('transactiondate',$transactiondate)->unique('refno');
        
        $this->processbook($receipts,$debits);
        $this->forwarded($rangedate,$transactiondate,$debits);
        
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
        $pdf->setPaper([0,0,612.00,936.00],'landscape');
        //$pdf->setPaper('legal','landscape');
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
    
    function processbook($receipts,$debits){
        ini_set('max_execution_time', 300);
        
        //Delete old entry
        \App\RptCashreceiptBook::where('idno',\Auth::user()->idno)->delete();
        \App\RptCashReceiptBookSundries::where('idno',\Auth::user()->idno)->delete();
        
        
        foreach($receipts as $receipt){
            $credit = \App\Credit::select(DB::raw('idno,transactiondate,receiptno,refno,accountingcode,acctcode,amount,isreverse'))->where('refno',$receipt->refno)->get();
            $debit  = $debits->where('refno',$receipt->refno,false);
            
            //Process Debit Sundries
            $debitSundries = $debit->filter(function($item){
                                            return !in_array(data_get($item, 'paymenttype'), array('1','4'));
                                                });
            $this->processSundries($debitSundries,'debit');
            
            //Process Credit Sundries
            $creditSundries = $credit->filter(function($item){
                                            return !in_array(data_get($item, 'accountingcode'), array(420200,420400,440400,420100,420000,120100,410000,210400));
                                                });   
             $this->processSundries($creditSundries,'credit');
            
            $rowcount = $this->rowCount($receipt->refno);

            $reportEntry = new \App\RptCashreceiptBook();
            $reportEntry->idno = \Auth::user()->idno;
            $reportEntry->from = $receipt->receivefrom;
            $reportEntry->refno = $receipt->refno;
            $reportEntry->transactiondate = $receipt->transactiondate;
            $reportEntry->receiptno = $receipt->receiptno;
            $reportEntry->cash = $debit->where('paymenttype',1,false)->sum('amount')+$debit->where('paymenttype',1,false)->sum('checkamount');
            $reportEntry->discount = $debit->where('paymenttype',4,false)->sum('amount');
            $reportEntry->dsundry = $debitSundries->sum('amount');
            
            $reportEntry->elearning = $credit->where('accountingcode',420200,false)->sum('amount');
            $reportEntry->misc = $credit->where('accountingcode',420400,false)->sum('amount');
            $reportEntry->book = $credit->where('accountingcode',440400,false)->sum('amount');
            $reportEntry->dept = $credit->where('accountingcode',420100,false)->sum('amount');
            $reportEntry->registration = $credit->where('accountingcode',420000,false)->sum('amount');
            $reportEntry->tuition = $credit->whereIn('accountingcode',array(120100,410000),false)->sum('amount');
            $reportEntry->creservation = $credit->where('accountingcode',210400,false)->sum('amount');                
            $reportEntry->csundry = $creditSundries->sum('amount');                                    
            $reportEntry->row_count = $rowcount;
            $reportEntry->isreverse = $receipt->isreverse;
            $reportEntry->save();
        }
    }
    
    function rowCount($refno){
        $sundries = \App\RptCashReceiptBookSundries::where('refno',$refno)->get();
        if($sundries->count() > 0){
            return $sundries->count();
        }else{
            return 1;
        }

        
    }
    
    function processSundries($accounts,$table){
           
           $entries = $accounts->groupBy("accountingcode");
           $rowcount = 0;
           foreach($entries as $entry){
               $debit = 0;
               $credit = 0;
               $sundries = \App\RptCashReceiptBookSundries::where('idno',\Auth::user()->idno)
                       ->where('refno',$entry->pluck('refno')->last())
                       ->where('accountingcode',$entry->pluck('accountingcode')->last())->first();
               if(!$sundries){
                    $sundries = new \App\RptCashReceiptBookSundries();
                    $sundries->idno = \Auth::user()->idno;
                    $sundries->refno = $entry->pluck('refno')->last();
                    $sundries->particular = $entry->pluck('acctcode')->last();
                    $sundries->accountingcode = $entry->pluck('accountingcode')->last();
                    $rowcount++;
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
    
    function forwarded($rangedate,$transactiondate,$debits){
            $credit = \App\Credit::select(DB::raw('idno,transactiondate,receiptno,refno,accountingcode,acctcode,sum(amount) as totalamount,isreverse'))
                    ->whereBetween('transactiondate', array($rangedate."-01",$transactiondate))
                    ->where('transactiondate','NOT LIKE',$transactiondate)
                    ->where('entry_type',1)
                    ->where('isreverse',0)
                    ->groupBy('accountingcode')
                    ->get();
            
            $debit  = $debits->where('isreverse',0,false)->filter(function($item) use($transactiondate){
                return data_get($item, 'transactiondate') !== $transactiondate;
                });
                
            $debitSundries = $debit->filter(function($item){
                                                return !in_array($item->paymenttype, array('1','4'));
                                                    });    
                
                $reportEntry = new \App\RptCashreceiptBook();
                $reportEntry->idno = \Auth::user()->idno;
                $reportEntry->refno = "forwarded";
                $reportEntry->cash = $debit->where('paymenttype',1,false)->sum('amount')+$debit->where('paymenttype',1,false)->sum('checkamount');
                $reportEntry->discount = $debit->where('paymenttype',4,false)->sum('amount');
                $reportEntry->dsundry = $debitSundries->sum('amount') + $debitSundries->sum('checkamount');
                
                $reportEntry->elearning = $credit->where('accountingcode',420200,false)->sum('totalamount');
                $reportEntry->misc = $credit->where('accountingcode',420400,false)->sum('totalamount');
                $reportEntry->book = $credit->where('accountingcode',440400,false)->sum('totalamount');
                $reportEntry->dept = $credit->where('accountingcode',420100,false)->sum('totalamount');
                $reportEntry->registration = $credit->where('accountingcode',420000,false)->sum('totalamount');
                $reportEntry->tuition = $credit->whereIn('accountingcode',array(120100,410000),false)->sum('totalamount');
                $reportEntry->creservation = $credit->where('accountingcode',210400,false)->sum('totalamount');
                $reportEntry->csundry = $credit->filter(function($item){
                                                return !in_array(data_get($item, 'accountingcode'), array(420200,420400,440400,420100,420000,120100,410000,210400));
                                                    })->sum('totalamount');
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
    
    function createEntry($from,$now){
        $debits  =  \App\Dedit::select(DB::raw('idno,transactiondate,receiptno,refno,accountingcode,acctcode,amount,isreverse,checkamount,receivefrom,paymenttype'))->whereBetween('transactiondate', array($from,$now))->where('entry_type',1)->orderBy('receiptno')->get();
        $receipts = $debits->where('transactiondate',$now)->unique('refno');
        
        
        foreach($receipts as $receipt){
            echo $receipt;
            $credits = \App\Credit::select(DB::raw('idno,transactiondate,receiptno,refno,accountingcode,acctcode,amount,isreverse'))->where('refno',$receipt->refno)->get();
            foreach($credits as $credit){
                echo "<br>".$credit;
            }
        }
    }
}
