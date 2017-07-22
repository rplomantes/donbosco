<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Cashier\CashierController;
use Carbon\Carbon;

class DBFixer extends Controller
{
    function addOldStudent(){
        return view('vincent.tools.addOldStudent');
    }
    
    function gensubjects(){
        $subjs = DB::connection('dbti2test')->select("Select * from subject");
        return view('vincent.tools.gensubj',compact('subjs'));
    }
    
    function updateentrytype(){
        $accounts = \App\Dedit::distinct('refno')->where('paymenttype','1')->get();
        
        foreach($accounts as $account){
            \App\Dedit::where('refno',$account->refno)->update(['entry_type'=>1]);
            //echo $account->refno;
        }
        return null;
    }
    
    function updatetvet(){
        $statuses = \App\Status::where('period','86')->get();
        
        foreach($statuses as $status){
            //$credits = \App\Credit::where('accountingcode','440000')->where('idno',$status->idno)->where('isreverse',0)->get();
            $credits = DB::Select("select * from old_receipts where idno = '$status->idno'");
            $amounted = 0;
            
            foreach($credits as $credit){
                if(count($credits) > 0){
                    $amounted = $amounted + $credit->amount;
                }
            }
            $exist = \App\Ledger::where('idno',$status->idno)->where('period','86')->exists();
            if($exist){
                $ledger = \App\Ledger::where('idno',$status->idno)->where('period','86')->first();
                //$ledger->payment = $amounted;
                $ledger->payment = $ledger->payment + $amounted;
                $ledger->save();                
            }

        }
    }    
    
   function getAdvancedStudents(){
       $totalelearnig = 0;
       $totalmisc = 0;
       $totaldept = 0;
       $totalreg = 0;
       $totaltuition = 0;
       $totalbook = 0;
       $totalreservation = 0;
       $totalother = 0;
       
       $totaldiscelearnig = 0;
       $totaldiscmisc = 0;
       $totaldiscdept = 0;
       $totaldiscreg = 0;
       $totaldisctuition = 0;
       $totaldiscbook = 0;
       $totaldiscother = 0;
       
       $totalfapeelearnig = 0;
       $totalfapemisc = 0;
       $totalfapedept = 0;
       $totalfapereg = 0;
       $totalfapetuition = 0;
       $totalfapebook = 0;
       $totalfapeother = 0;
       
       $totalreselearnig = 0;
       $totalresmisc = 0;
       $totalresdept = 0;
       $totalresreg = 0;
       $totalrestuition = 0;
       $totalresbook = 0;
       
       $totaldepelearnig = 0;
       $totaldepmisc = 0;
       $totaldepdept = 0;
       $totaldepreg = 0;
       $totaldeptuition = 0;
       $totaldepbook = 0;
       $totaldepother = 0;
       
       $totaldebreservation = 0;
       $totalregreservation = 0;
       
       $depositbreakdown = array();
       
       $remfape = 0;
       $remsd = 0;
       
       $crossmoney = 0;
       $sy = 2017;
       //$receipts = DB::Select("Select distinct refno from statuses s join credits c on s.idno = c.idno and s.schoolyear = c.schoolyear where s.schoolyear = $sy and s.status = 2 and isreverse = 0 and (date_enrolled between '2017-02-01' AND '2017-04-31') and (transactiondate between '2017-02-01' AND '2017-04-31') and accountingcode in (420200,420400,420100,420000,120100,440400) and s.department != 'TVET' and referenceid != ''");

       $receipts = DB::Select("Select distinct refno from statuses s join dedits c on s.idno = c.idno and s.schoolyear = c.schoolyear where s.schoolyear = $sy and s.status = 2 and isreverse = 0 and (date_enrolled between '2017-02-01' AND '2017-04-31') and (transactiondate between '2017-02-01' AND '2017-04-31') and description = 'Student Deposit' and s.department != 'TVET'");
       foreach($receipts as $receipt){

           $elearnig = 0;
           $misc = 0;
           $dept = 0;
           $reg = 0;
           $tuition = 0;
           $book = 0;
           $reservation = 0;
           $other = 0;
//           
           $discelearnig = 0;
           $discmisc = 0;
           $discdept = 0;
           $discreg = 0;
           $disctuition = 0;
           $discbook = 0;
//
           $fapeelearnig = 0;
           $fapemisc = 0;
           $fapedept = 0;
           $fapereg = 0;
           $fapetuition = 0;
           $fapebook = 0;
           $fapeother = 0;
//
//           $reselearnig = 0;
//           $resmisc = 0;
//           $resdept = 0;
//           $resreg = 0;
//           $restuition = 0;
//           $resbook = 0;
//
           $depelearnig = 0;
           $depmisc = 0;
           $depdept = 0;
           $depreg = 0;
           $deptuition = 0;
           $depbook = 0;
           $depother = 0;
           
           $fapedother = 0;
           $fapedbook = 0;
           $fapedreg = 0;
           $fapeddept = 0;
           $fapedmisc = 0;
           $fapedelearnig = 0;
           $fapedtuition = 0;
           
           $discountedt = 0;
           $reservedreg = 0;
           
           $debreservation = 0;
           $regreservation = 0;
           $hasReservation = 0;
           
           $sd = 0;
           $fape = 0;

           $accounts = \App\Credit::where('refno',$receipt->refno)->get();
//           
           $divider = DB::Select("Select * from credits where refno = $receipt->refno group by accountingcode");
//           
           foreach($accounts as $account){
               if($account->accountingcode == 420200 && $account->schoolyear == 2017){
                   $elearnig = $elearnig + $account->amount;
}
               if($account->accountingcode == 420400 && $account->schoolyear == 2017){
                   $misc = $misc + $account->amount;
               }
               if($account->accountingcode == 420100 && $account->schoolyear == 2017){
                   $dept = $dept + $account->amount;
               }
               if($account->accountingcode == 420000 && $account->schoolyear == 2017){
                   $reg = $reg + $account->amount;
               }
               if($account->accountingcode == 120100 && $account->schoolyear == 2017){
                   $tuition = $tuition + $account->amount;
               }
               if($account->accountingcode == 440400 && $account->schoolyear == 2017){
                   $book = $book + $account->amount;
               }
               if($account->accountingcode == 210400 && $account->schoolyear == 2017){
                   $reservation = $reservation + $account->amount;
                   $hasReservation = 1;
               }
               if($account->schoolyear != 2017){
                   $other = $other + $account->amount;
               }
           }
//           
           $noncashes = \App\Dedit::where('refno',$receipt->refno)->whereIn('paymenttype',array(2,3,4,5,6,7,8,9))->get();  
           
           foreach($noncashes as $noncash){

               if(($noncash->accountingcode == 410100) || ($noncash->accountingcode == 410200) || ($noncash->accountingcode == 500300) || ($noncash->accountingcode == 530200)){
                   $disctuition = $disctuition + $noncash->amount;
               }
               
               $discountedt = $tuition - $disctuition;
               
               if($noncash->accountingcode == 210400){
                   if($hasReservation == 0){
                       $regreservation = $regreservation + $noncash->amount;
                   }else{
                       $debreservation = $debreservation + $noncash->amount;
                   }
               }
               
               $reservedreg = $reg - $regreservation;
               
               if($noncash->description == 'FAPE'){
                   $fape = $noncash->amount;
                   
                   if($fape > 0){
                       $fapeother = $this->addminus($other,$fape);
                       $fape = $fape - $fapeother;
                   }
                   if($fape>0){
                       $fapebook = $this->addminus($book,$fape);
                       $fape = $fape - $fapebook;
                   }
                   if($fape>0){
                       $fapereg = $this->addminus($reservedreg,$fape);
                       $fape = $fape - $fapereg;
                   }
                   if($fape>0){
                       $fapedept = $this->addminus($dept,$fape);
                       $fape = $fape - $fapedept;
                   }
                   if($fape>0){
                       $fapemisc = $this->addminus($misc,$fape);
                       $fape = $fape - $fapemisc;
                   }
                   if($fape>0){
                       $fapeelearnig = $this->addminus($elearnig,$fape);
                       $fape = $fape - $fapeelearnig;
                   }
                   if($fape>0){
                       $fapetuition = $this->addminus($discountedt,$fape);
                       $fape = $fape - $fapetuition;
                   }
               }
               
               $fapedother = $other - $fapeother;
               $fapedbook = $book - $fapebook;
               $fapedreg = $reg - $fapereg;
               $fapeddept = $dept - $fapedept;
               $fapedmisc = $misc - $fapemisc;
               $fapedelearnig = $elearnig - $fapeelearnig;
               $fapedtuition = $tuition - ($fapetuition+$discountedt);
               
               if($noncash->description == 'Student Deposit'){
                   $sd = $noncash->amount;
                   if($sd>0){
                       $depother = $this->addminus($fapedother,$sd);
                       $sd = $sd - $depother;
                   }
                   if($sd>0){
                       $depbook = $this->addminus($fapedbook,$sd);
                       $sd = $sd - $depbook;
                   }
                   if($sd>0){
                       $depreg = $this->addminus($fapedreg,$sd);
                       $sd = $sd - $depreg;
                   }
                   if($sd>0){
                       $depdept = $this->addminus($fapeddept,$sd);
                       $sd = $sd - $depdept;
                   }
                   if($sd>0){
                       $depmisc = $this->addminus($fapedmisc,$sd);
                       $sd = $sd - $depmisc;
                   }
                   if($sd>0){
                       $depelearnig = $this->addminus($fapedelearnig,$sd);
                       $sd = $sd - $depelearnig;
                   }
                   if($sd>0){
                       $deptuition = $this->addminus($discountedt+$fapedtuition,$sd);
                       $sd = $sd - $deptuition;
                   }
                   $depositbreakdown[] =array('trans'=>$noncash->transactiondate,'payer'=>$noncash->receivefrom,'receipt'=>$noncash->receiptno,'deposit'=>$noncash->amount,'sundry'=>$depother,'book'=>$depbook,'registration'=>$depreg,'department'=>$depdept,'misc'=>$depmisc,'elearnign'=>$depelearnig,'tuition'=>$deptuition); 
               }
               
               
           }
           
       $totalelearnig = $totalelearnig + $elearnig;
       $totalmisc = $totalmisc + $misc;
       $totaldept = $totaldept + $dept;
       $totalreg = $totalreg + $reg;
       $totaltuition = $totaltuition + $tuition;
       $totalbook = $totalbook + $book;
       $totalreservation = $totalreservation + $reservation;
       $totalother = $totalother + $other;
       
       $totaldiscelearnig = $totaldiscelearnig + $discelearnig;
       $totaldiscmisc = $totaldiscmisc + $discmisc;
       $totaldiscdept = $totaldiscdept + $discdept;
       $totaldiscreg = $totaldiscreg + $discreg;
       $totaldisctuition = $totaldisctuition + $disctuition;
       $totaldiscbook = $totaldiscbook + $discbook;
       
       $totalfapeelearnig = $totalfapeelearnig + $fapeelearnig;
       $totalfapemisc = $totalfapemisc + $fapemisc;
       $totalfapedept = $totalfapedept + $fapedept;
       $totalfapereg = $totalfapereg + $fapereg;
       $totalfapetuition = $totalfapetuition + $fapetuition;
       $totalfapebook = $totalfapebook + $fapebook;
       $totalfapeother = $totalfapeother + $fapeother;
       
       $totaldepelearnig = $totaldepelearnig + $depelearnig;
       $totaldepmisc = $totaldepmisc + $depmisc;
       $totaldepdept = $totaldepdept + $depdept;
       $totaldepreg = $totaldepreg + $depreg;
       $totaldeptuition = $totaldeptuition + $deptuition;
       $totaldepbook = $totaldepbook + $depbook;
       $totaldepother = $totaldepother + $depother;
       
       $remfape = $fape;
       $remsd = $sd;
       
       $totaldebreservation = $totaldebreservation + $debreservation;
       $totalregreservation = $totalregreservation + $regreservation;

       }
       

       return view('report',compact('totalelearnig','totalmisc','totaldept','totalreg','totaltuition','totalbook','totalreservation','totalother','totaldiscelearnig','totaldiscmisc','totaldiscdept','totaldiscreg','totaldisctuition','totaldiscbook','totalfapeelearnig','totalfapemisc','totalfapedept','totalfapereg','totalfapetuition','totalfapebook','totalfapeother','totaldepelearnig','totaldepmisc','totaldepdept','totaldepreg','totaldeptuition','totaldepbook','totaldepother','remfape','remsd','totaldebreservation','totalregreservation','depositbreakdown','receipts'));
   }
   
   function addminus($account,$amount){
       if($account >= $amount){
           $returned= $amount;
       }else{
           $returned= $account;
       }
       
       return $returned;
   }
   
   function fixYouthAssistance(){
       $accounts = array(376933,376934,376935,376936,376937);
       $refno  = 3342122220;
       $orno = 487558;
       $totaldisc = 0;
       $idno = 0;
       $discountcode = 0;
       
       foreach($accounts as $account){
           $process = \App\Ledger::find($account);
           CashierController::credit($process->idno, $process->id, $refno, $orno, $process->otherdiscount);
           $totaldisc = $totaldisc + $process->otherdiscount;
           $discountcode = $process->discountcode;
           $idno = $process->idno;
       }
       
       
       $this->discount($refno, $orno,$idno,$totaldisc,$discountcode);
       
       return $idno;
   }
   
   function discount($refno, $orno,$idno,$amount,$discountcode){
        $student = \App\User::where('idno',$idno)->first();
        $status = \App\Status::where('idno',$idno)->first();
        $fiscal = \App\CtrFiscalyear::first();
        $discount = \App\CtrDiscount::where('discountcode',$discountcode)->first();
        $debitaccount = new \App\Dedit;
        $debitaccount->idno = $idno;
        $debitaccount->fiscalyear=$fiscal->fiscalyear;
        $debitaccount->transactiondate = Carbon::now();
        $debitaccount->accountingcode = $discount->accountingcode;
        $debitaccount->acctcode = $discount->acctname;
        $debitaccount->description = $discount->description;
        $debitaccount->refno = $refno;
        $debitaccount->entry_type = '1';
        $debitaccount->receiptno = $orno;
        $debitaccount->paymenttype = 4;
        $debitaccount->receivefrom = $student->lastname . ", " . $student->firstname . " " . $student->extensionname . " " .$student->middlename;
        $debitaccount->amount = $amount;
        if(count($status)>0){
            if($status->department == "Kindergarten" ||$status->department == "Elementary"){
                $debitaccount->acct_department = "Elementary Department";
                $debitaccount->sub_department = "Elementary Department";
            }elseif($status->department == "Junior High School" ||$status->department == "Senior High School"){
                $debitaccount->acct_department = "High School Department";
                $debitaccount->sub_department = "High School Department";
            }elseif($status->department == "TVET"){
                $debitaccount->acct_department = "TVET";
                $debitaccount->sub_department = "TVET";
            }
            $debitaccount->schoolyear=$status->schoolyear;
        }else{
                $debitaccount->acct_department = "None";
                $debitaccount->sub_department = "None";
        }
        $debitaccount->postedby = \Auth::user()->idno;
        $debitaccount->save();
        
    }
   
   function fixdiscount($refno){
        $credits = \App\Credit::where('refno',$refno)->get();
        $plandiscount = 0;
        $otherdiscount = array();
        
        \App\Dedit::where('refno',$refno)->where('paymenttype',4)->delete();
        
        $orno = "";
        $idno = "";
        
       foreach($credits as $credit){
            $orno = $credit->receiptno;
            $idno = $credit->idno;
            echo $credit->referenceid."<br>";
           $ledgers = \App\Ledger::find($credit->referenceid);
            if($ledgers->plandiscount > 0){
                $plandiscount = $plandiscount + $ledgers->plandiscount;
            }
            if($ledgers->otherdiscount > 0){
                if(array_key_exists($ledgers->discountcode, $otherdiscount)){
                    $acctdisc = $otherdiscount [$ledgers->discountcode];
                }else{
                    $acctdisc = 0;
                }
                $otherdiscount [$ledgers->discountcode]= $acctdisc + $ledgers->otherdiscount;
            }
       }
       
      if($plandiscount > 0){
            $this->debit_discount_fix($refno, $orno,$idno,env('DEBIT_DISCOUNT') , $plandiscount, "Plan Discount");
      }
      
      if(count($otherdiscount) > 0){
          foreach($otherdiscount as $key =>$amount){
            $this->debit_discount_fix($refno, $orno,$idno,env('DEBIT_DISCOUNT') , $amount, $key);    
          }
      }
   }
   
   function debit_discount_fix($refno, $orno,$idno,$debittype,$amount,$discountname){
       $department = "";
        if($discountname == "Plan Discount"){
            $accountcode='410100';
            $acctcode='Cash/Semi payment discount';
            $description = 'Plan Discount';
        }else{
            $discount = \App\CtrDiscount::where('discountcode',$discountname)->first();
            if(count($discount)>0){
                $accountcode = $discount->accountingcode;
                $acctcode = $discount->acctname;
                $description = $discount->description;
                $department = $discount->sub_department;
                
            }else{
                $accountcode= '0';
                $acctcode='Unknown';
                $description = 'Unknown';
            }

        }
        $student = \App\User::where('idno',$idno)->first();
        $status = \App\Status::where('idno',$idno)->first();
        $fiscal = \App\CtrFiscalyear::first();        
        $debitaccount = new \App\Dedit;
        $debitaccount->idno = $idno;
        $debitaccount->fiscalyear=$fiscal->fiscalyear;
        $debitaccount->transactiondate = Carbon::now();
        $debitaccount->accountingcode = $accountcode;
        $debitaccount->acctcode = $acctcode;
        $debitaccount->description = $description;
        $debitaccount->refno = $refno;
        $debitaccount->entry_type = '1';
        $debitaccount->receiptno = $orno;
        $debitaccount->paymenttype = $debittype;
        $debitaccount->receivefrom = $student->lastname . ", " . $student->firstname . " " . $student->extensionname . " " .$student->middlename;
        $debitaccount->amount = $amount;
        if($department == ""){
            if(count($status)>0){
                if($status->department == "Kindergarten" ||$status->department == "Elementary"){
                    $debitaccount->acct_department = "Elementary Department";
                    $debitaccount->sub_department = "Elementary Department";
                }elseif($status->department == "Junior High School" ||$status->department == "Senior High School"){
                    $debitaccount->acct_department = "High School Department";
                    $debitaccount->sub_department = "High School Department";
                }elseif($status->department == "TVET"){
                    $debitaccount->acct_department = "TVET";
                    $debitaccount->sub_department = "TVET";
                }
                $debitaccount->schoolyear=$status->schoolyear;
            }else{
                    $debitaccount->acct_department = "None";
                    $debitaccount->sub_department = "None";
            }   
        }else{
            $debitaccount->acct_department = CashierController::mainDepartment($discount->sub_department);
            $debitaccount->sub_department = $discount->sub_department;
        }
        $debitaccount->postedby = \Auth::user()->idno;
        $debitaccount->save();
        
    }
}
