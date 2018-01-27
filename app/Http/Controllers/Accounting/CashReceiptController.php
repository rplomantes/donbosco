<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
class CashReceiptController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function cashreceiptbook($transactiondate){
        $rangedate = date("Y-m",strtotime($transactiondate));
        
        $debits = \App\Dedit::whereBetween('transactiondate', array($rangedate."-01",$transactiondate))->where('entry_type',1)->orderBy('receiptno')->get();
        $credits = \App\Credit::whereBetween('transactiondate', array($rangedate."-01",$transactiondate))->where('entry_type',1)->get();
        
        $receipts = $receipts = $debits->where('transactiondate',$transactiondate)->unique('refno');
        $this->processbook($receipts,$debits,$credits);
        
        $records = \App\RptCashreceiptBook::where('idno',\Auth::user()->idno)->get();
        
        return view('accounting.CashReceipt.printbook',compact('transactiondate','records'));
    }
    
    function processbook($receipts,$debits,$credits){
        ini_set('max_execution_time', 0);
        \App\RptCashreceiptBook::where('idno',\Auth::user()->idno)->delete();
        
        foreach($receipts as $receipt){
            $credit = $credits->where('refno',$receipt->refno);
            $debit  = $debits->where('refno',$receipt->refno);
            
                $reportEntry = new \App\RptCashreceiptBook();
                $reportEntry->idno = \Auth::user()->idno;
                $reportEntry->refno = $receipt->refno;
                $reportEntry->refno = $receipt->refno;
                $reportEntry->transactiondate = $receipt->transactiondate;
                $reportEntry->receiptno = $receipt->receiptno;
                $reportEntry->cash = $debit->where('paymenttype','1')->sum('amount')+$debit->where('paymenttype','1')->sum('checkamount');
                $reportEntry->discount = $debit->where('paymenttype','4')->sum('amount');
                $reportEntry->fape = $debit->where('paymenttype','7')->sum('amount');
                $reportEntry->dreservation = $debit->where('paymenttype','5')->sum('amount');
                $reportEntry->deposit = $debit->where('paymenttype','8')->sum('amount');
                
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
                $reportEntry->isreverse = $receipt->isreverse;
                $reportEntry->save();
                                                    
        }
    }
    
    function cashreceiptpdf(){
        $currTrans = \App\RptCashreceiptBook::where('idno', \Auth::user()->idno)->where('totalindic',0)->get();
        $forwarder = \App\RptCashreceiptBook::where('idno', \Auth::user()->idno)->where('totalindic',1)->where('isreverse',0)->get();
        //$forwarder = DB::Select("select sum(`cash`) as cash,sum(`discount`) as discount,sum(`fape`) as fape,sum(`dreservation`) as dreservation,sum(`deposit`) as deposit,sum(`elearning`) as elearning,sum(`book`) as book,sum(`dept`) as department,sum(`registration`) as registration,sum(`tuition`) as tuition,sum(`creservation`) as creservation,sum(`csundry`) as csundry,sum(`misc`) as misc from `rpt_cashreceipt_books` where `idno` = '".\Auth::user()->idno."' and `totalindic` = 1 and `isreverse` = 0");
        if(count($currTrans)> 0){
            $date = \App\RptCashreceiptBook::where('idno', \Auth::user()->idno)->where('totalindic',0)->first()->transactiondate;
        }else{
            $date = session('cashdate');
        }
        
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView('print.cashreceiptspdf',compact('currTrans','forwarder','date'));
        return $pdf->stream();
    }

    function breakdownpdf($fromtran,$totran){
        $breakdown = DB::Select("Select accountingcode, acctcode, sum(amount) as amount from credits where accountingcode NOT IN (120100,410000,420000,420100,440400,420400,420200,210400) and (transactiondate between '$fromtran' and '$totran') and entry_type = 1 and isreverse = 0 group by accountingcode order by accountingcode");

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('legal','portrait');
        $pdf->loadView('print.printbreakdownpdf',compact('breakdown','fromtran','totran'));
        return $pdf->stream();
    }
    
    function debitcashreceipts($receipts,$indic){
        foreach($receipts as $receipt){
            $existing = \App\RptCashreceiptBook::where('refno',$receipt->refno)->exists();
            if($existing){
                $record = \App\RptCashreceiptBook::where('refno',$receipt->refno)->first();
            }else{
                $record =new \App\RptCashreceiptBook;
                $record->idno = \Auth::user()->idno;
                $record->from = $receipt->receivefrom;
                $record->receiptno = $receipt->receiptno;
                $record->refno = $receipt->refno;
                $record->transactiondate = $receipt->transactiondate;
                $record->isreverse = $receipt->isreverse;
                $record->totalindic = $indic;
            }

            switch($receipt->paymenttype){
                case 1:
                    $record->cash = $record->cash + ($receipt->amount + $receipt->checkamount);
                    break;
                case 4:
                    $record->discount = $record->discount + ($receipt->amount + $receipt->checkamount);
                    break;
                case 5:
                    $record->dreservation = $record->dreservation + ($receipt->amount + $receipt->checkamount);
                    break;
                case 7:
                    $record->fape = $record->fape + ($receipt->amount + $receipt->checkamount);
                    break;
                case 8:
                    $record->deposit = $record->deposit + ($receipt->amount + $receipt->checkamount);
                    break;
            }
            $record->save();
        }
        
        return null;
    }
    
    function creditcashreceipts($receipts){
        foreach($receipts as $receipt){
            $existing = \App\Credit::where('refno',$receipt->refno)->exists();
            if($existing){
                $record = \App\RptCashreceiptBook::where('refno',$receipt->refno)->first();
                $record->elearning = $receipt->elearning;
                $record->misc = $receipt->misc;
                $record->book =$receipt->books;
                $record->dept = $receipt->dept;
                $record->registration = $receipt->registration;
                $record->tuition =  $receipt->tuition;
                $record->creservation = $receipt->creservation;
                $record->csundry =$receipt->others;
                $record->save();
                
            }
            
            
        }
    }
    
    function cashreceipts($transactiondate){
    $rangedate = date("Y-m",strtotime($transactiondate));
    $asOf = date("l, F d, Y",strtotime($transactiondate));
    $wilddate = $rangedate."-%";
    $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, dedits.idno,dedits.receivefrom,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from dedits where "
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '1' group by dedits.idno, dedits.transactiondate, dedits.postedby, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
      
     $otheraccounts = DB::Select("select sum(credits.amount) as amount, credits.receipt_details, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from users, dedits, credits where users.idno = dedits.idno and"
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and credits.refno=dedits.refno and credits.categoryswitch >= '7'   and credits.receipt_details != 'Reservation' and dedits.paymenttype = '1' group by users.idno, dedits.transactiondate, dedits.postedby, users.lastname, "
                . " users.firstname, credits.receipt_details, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
    
     $othersummaries = DB::Select("select sum(credits.amount) as amount, credits.acctcode, "
                . " dedits.transactiondate from users, dedits, credits where users.idno = dedits.idno and"
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and credits.refno=dedits.refno and credits.categoryswitch >= '7'  and credits.acctcode != 'Reservation' and dedits.paymenttype = '1' and dedits.isreverse = '0' group by  dedits.transactiondate, "
                . " credits.acctcode order by credits.acctcode" );
     
   //FORWARDED BALANCE  
     
    $totalmonthbal = DB::Select("SELECT sum(cash) as cash,sum(discount) as discount,sum(d.reservation) as dreserve,sum(fape) as fape, sum(student_deposit) as deposit,sum(books) as books,sum(elearning) as elearning,sum(misc) as misc,sum(dept) as dept,sum(registration) as registration,sum(tuition) as tuition,sum(c.reservation) as creservation,sum(others) as others FROM `receiptdedits` d join receiptcredits c on d.refno = c.refno where "
                . "d.isreverse = '0' and d.transactiondate like '$wilddate' and d.transactiondate < '$transactiondate'");
    
   $totalcashdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '1' and isreverse = '0'" );
   
   $totalcash=0.00;
   
   foreach($totalcashdb as $tcd){
       $totalcash = $totalcash + $tcd->amount + $tcd->checkamount;
   }
  
   $totaldiscountdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '4' and isreverse = '0'" );
   if(count($totaldiscountdb)>0){
    $totaldiscount = $totaldiscountdb[0]->amount + $totaldiscountdb[0]->checkamount;
   }else{
   $totaldiscount = 0;
   }
   
   $drreservationdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '5' and isreverse = '0'" );
   if(count($drreservationdb)>0){
        $drreservation = $drreservationdb[0]->amount + $drreservationdb[0]->checkamount;
    }else {
       $drreservation=0;
    }
    
   $totalfapedb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '4' and isreverse = '0'" );
   if(count($totalfapedb)>0){
    $totalfape = $totalfapedb[0]->amount;
   }else{
   $totalfape = 0;
   }
    
   $elearningcr = $this->getcrmonthmain(1, $wilddate, $transactiondate);
   $misccr = $this->getcrmonthmain(2, $wilddate, $transactiondate);
   $bookcr = $this->getcrmonthmain(3, $wilddate, $transactiondate);
   $departmentcr = $this->getcrmonthmain(4, $wilddate, $transactiondate);
   $registrationcr =$this->getcrmonthmain(5, $wilddate, $transactiondate);
   $tuitioncr = $this->getcrmonthmain(6, $wilddate, $transactiondate);
   $crreservationdb = DB::Select("Select sum(amount) as amount from credits where transactiondate like "
           . "'$wilddate' and transactiondate < '$transactiondate' and categoryswitch = '9' and acctcode ='Reservation'");
   if(count($crreservationdb)>0){
       $crreservation = $crreservationdb[0]->amount;
   } else {
       $crreservation = 0;
   }
   
   
   $crothersdb = DB::Select("Select sum(amount) as amount from credits where transactiondate like "
           . "'$wilddate' and transactiondate < '$transactiondate' and categoryswitch >= '7' and acctcode !='Reservation' and isreverse = '0'");
   if(count($crothersdb)>0){
       $crothers = $crothersdb[0]->amount;
   } else {
       $crothers = 0;
   }
   //END FORWARD BALANCE
   
   
   $forward = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from users, dedits where users.idno = dedits.idno and"
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and dedits.transactiondate < '$transactiondate'" );
   $forwardbal = $forward[0]->amount+$forward[0]->checkamount;
     
    $allcollections = array();
    $int=0;
    
    
foreach ($collections as $collection){
    $allcollections[$int] = array(
        $collection->receiptno,
        $collection->receivefrom,
        $collection->amount+$collection->checkamount, 
        $this->getReservationDebit($collection->refno),
        $this->getcreditamount($collection->refno,1),
        $this->getcreditamount($collection->refno,2),
        $this->getcreditamount($collection->refno,3),
        $this->getcreditamount($collection->refno,4),
        $this->getcreditamount($collection->refno,5),
        $this->getcreditamount($collection->refno,6),
        $this->getReservationCredit($collection->refno),
        $this->getcreditamount1($collection->refno,7),
        $collection->isreverse,
        $this->getDiscount($collection->refno),
        $this->getFapeDebit($collection->refno)
            
        );
    
    $int=$int+1;
}
    
    //return $othersummaries;
    return view('accounting.cashreceiptdetails',compact('elearningcr','misccr','bookcr','departmentcr','registrationcr','tuitioncr','crreservation','crothers','totalcash','totaldiscount','drreservation','allcollections','transactiondate','otheraccounts','othersummaries','forwardbal','asOf','totalfape','totalmonthbal'));
    //return $forwardbal;
}

    function printcashreceipts($transactiondate){
    $rangedate = date("Y-m",strtotime($transactiondate));
    $asOf = date("l, F d, Y",strtotime($transactiondate));
    $wilddate = $rangedate."-%";
    $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, dedits.idno,dedits.receivefrom,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from dedits where "
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '1' group by dedits.idno, dedits.transactiondate, dedits.postedby, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
  
    
    //FORWARD BALANCE
    $totalmonthbal = DB::Select("SELECT sum(cash) as cash,sum(discount) as discount,sum(d.reservation) as dreserve,sum(fape) as fape, sum(student_deposit) as deposit,sum(books) as books,sum(elearning) as elearning,sum(misc) as misc,sum(dept) as dept,sum(registration) as registration,sum(tuition) as tuition,sum(c.reservation) as creservation,sum(others) as others FROM `receiptdedits` d join receiptcredits c on d.refno = c.refno where "
                . "d.isreverse = '0' and d.transactiondate like '$wilddate' and d.transactiondate < '$transactiondate'");
    
    
   $totalcashdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '1' and isreverse = '0'" );
   
   $totalcash=0.00;
   
   foreach($totalcashdb as $tcd){
       $totalcash = $totalcash + $tcd->amount + $tcd->checkamount;
   }
  
   $totaldiscountdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '4' and isreverse = '0'" );
   if(count($totaldiscountdb)>0){
    $totaldiscount = $totaldiscountdb[0]->amount + $totaldiscountdb[0]->checkamount;
   }else{
   $totaldiscount = 0;
   }
   
   $drreservationdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '5' and isreverse = '0'" );
   if(count($drreservationdb)>0){
        $drreservation = $drreservationdb[0]->amount + $drreservationdb[0]->checkamount;
    }else {
       $drreservation=0;
    }
    
   $totalfapedb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '4' and isreverse = '0'" );
   if(count($totalfapedb)>0){
    $totalfape = $totalfapedb[0]->amount;
   }else{
   $totalfape = 0;
   }
    
   $elearningcr = $this->getcrmonthmain(1, $wilddate, $transactiondate);
   $misccr = $this->getcrmonthmain(2, $wilddate, $transactiondate);
   $bookcr = $this->getcrmonthmain(3, $wilddate, $transactiondate);
   $departmentcr = $this->getcrmonthmain(4, $wilddate, $transactiondate);
   $registrationcr =$this->getcrmonthmain(5, $wilddate, $transactiondate);
   $tuitioncr = $this->getcrmonthmain(6, $wilddate, $transactiondate);
   $crreservationdb = DB::Select("Select sum(amount) as amount from credits where transactiondate like "
           . "'$wilddate' and transactiondate < '$transactiondate' and categoryswitch = '9' and acctcode ='Reservation'");
   if(count($crreservationdb)>0){
       $crreservation = $crreservationdb[0]->amount;
   } else {
       $crreservation = 0;
   }
   
   
   $crothersdb = DB::Select("Select sum(amount) as amount from credits where transactiondate like "
           . "'$wilddate' and transactiondate < '$transactiondate' and categoryswitch >= '7' and acctcode !='Reservation' and isreverse = '0'");
   if(count($crothersdb)>0){
       $crothers = $crothersdb[0]->amount;
   } else {
       $crothers = 0;
   }
  
   //END FORWARD BALANCE
   
   
   $forward = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from users, dedits where users.idno = dedits.idno and"
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and dedits.transactiondate < '$transactiondate'" );
   $forwardbal = $forward[0]->amount+$forward[0]->checkamount;
     
    $allcollections = array();
    $int=0;
    
    
foreach ($collections as $collection){
    $allcollections[$int] = array(
        $collection->receiptno,
        $collection->receivefrom,
        $collection->amount+$collection->checkamount, 
        $this->getReservationDebit($collection->refno),
        $this->getcreditamount($collection->refno,1),
        $this->getcreditamount($collection->refno,2),
        $this->getcreditamount($collection->refno,3),
        $this->getcreditamount($collection->refno,4),
        $this->getcreditamount($collection->refno,5),
        $this->getcreditamount($collection->refno,6),
        $this->getReservationCredit($collection->refno),
        $this->getcreditamount1($collection->refno,7),
        $collection->isreverse,
        $this->getDiscount($collection->refno),
        $this->getFapeDebit($collection->refno)
        );
    $int=$int+1;
}
       $pdf = \App::make('dompdf.wrapper');
       $pdf->setPaper('legal','landscape');
       $pdf->loadView('print.printcashreceipt',compact('elearningcr','misccr','bookcr','departmentcr','registrationcr','tuitioncr','crreservation','crothers','totalcash','totaldiscount','drreservation','allcollections','transactiondate','otheraccounts','othersummaries','forwardbal','asOf','totalfape','totalmonthbal'));
       return $pdf->stream();
    //return $forwardbal;
}

    function getcrmonthmain($cswitch, $monthdate, $trandate){
        $total = DB::Select("Select sum(Amount) as amount from credits where categoryswitch = '$cswitch' and "
                . "isreverse = '0' and transactiondate like '$monthdate' and transactiondate < '$trandate'");
        if(count($total)> 0){
            $credit = $total[0]->amount;
        } else {
            $credit=0;
        }
        return $credit;
    }
    
        function getReservationCredit($refno){
        $mt=0;
        $amount=  \App\Credit::where('refno',$refno)->where('acctcode','Reservation')->first();
        if(count($amount)>0){
            $mt = $amount->amount;
        }
        return $mt;
    }
    function getReservationDebit($refno){
        $mt=0;
        $amount = \App\Dedit::where('refno',$refno)->where('paymenttype','5')->first();
        if(count($amount)>0){
            $mt = $amount->amount;
        }
        return $mt;
        }
    function getFapeDebit($refno){
        $mt=0;
        $amount = \App\Dedit::where('refno',$refno)->where('paymenttype','7')->first();
        if(count($amount)>0){
            $mt = $amount->amount;
        }
        return $mt;
        }
    function getcreditamount($refno,$categoryswitch){
        $amount = DB::Select("select sum(amount) as amount from credits where refno = '$refno' and categoryswitch = '$categoryswitch'");
        
        foreach($amount as $mnt){
            $mt = $mnt->amount;
        }
        
        if(!isset($mt)){
            $mt=0;
        }
        return $mt;
    }
    
    function getcreditamount1($refno,$categoryswitch){
        $amount = DB::Select("select sum(amount) as amount from credits where refno = '$refno' and categoryswitch >= '$categoryswitch' and acctcode != 'Reservation'");
        
        foreach($amount as $mnt){
            $mt = $mnt->amount;
        }
        
        if(!isset($mt)){
            $mt=0;
        }
        return $mt;
    }
    


    function getDiscount($refno){
        $discount = \App\Dedit::where('refno',$refno)->where('paymenttype','4')->first();
        $mt=0;
        if(count($discount)>0){
          $mt=$discount->amount;  
        }
        return $mt;
    }
}
