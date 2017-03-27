<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CashReceiptController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function cashreceipts($transactiondate){
    $rangedate = date("Y-m",strtotime($transactiondate));
    $asOf = date("l, F d, Y",strtotime($transactiondate));
    $wilddate = $rangedate."-%";
    $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from users, dedits where users.idno = dedits.idno and "
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '1' group by users.idno, dedits.transactiondate, dedits.postedby, users.lastname, users.firstname, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
      
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
        $collection->lastname." ,".$collection->firstname,
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
    return view('accounting.cashreceiptdetails',compact('elearningcr','misccr','bookcr','departmentcr','registrationcr','tuitioncr','crreservation','crothers','totalcash','totaldiscount','drreservation','allcollections','transactiondate','otheraccounts','othersummaries','forwardbal','asOf','totalfape'));
    //return $forwardbal;
}

    function printcashreceipts($transactiondate){
    $rangedate = date("Y-m",strtotime($transactiondate));
    $asOf = date("l, F d, Y",strtotime($transactiondate));
    $wilddate = $rangedate."-%";
    $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from users, dedits where users.idno = dedits.idno and"
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '1' group by users.idno, dedits.transactiondate, dedits.postedby, users.lastname, users.firstname, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
  
    
    //FORWARD BALANCE
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
        $collection->lastname." ,".$collection->firstname,
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
       $pdf->loadView('print.printcashreceipt',compact('elearningcr','misccr','bookcr','departmentcr','registrationcr','tuitioncr','crreservation','crothers','totalcash','totaldiscount','drreservation','allcollections','transactiondate','otheraccounts','othersummaries','forwardbal','asOf','totalfape'));
       return $pdf->stream();
    //return $forwardbal;
}
}
