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
            $transaction = $credit->transactiondate;
            $idno = $credit->idno;
            echo $credit->referenceid."<br>";
           $ledgers = \App\Ledger::find($credit->referenceid);
           if(count($ledgers)>0){
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

       }
       
      if($plandiscount > 0){
            $this->debit_discount_fix($transaction,$refno, $orno,$idno,env('DEBIT_DISCOUNT') , $plandiscount, "Plan Discount");
      }
      
      if(count($otherdiscount) > 0){
          foreach($otherdiscount as $key =>$amount){
            $this->debit_discount_fix($transaction,$refno, $orno,$idno,env('DEBIT_DISCOUNT') , $amount, $key);    
          }
      }
      
      $renewed = \App\Dedit::where('refno',$refno)->get();
      return $renewed;
   }
   
   function debit_discount_fix($transaction,$refno, $orno,$idno,$debittype,$amount,$discountname){
       $department = "";
        if($discountname == "Plan Discount"){
            $accountcode='410100';
            $acctcode='Cash/Semi payment discount';
            $description = 'Plan Discount';
            $department = "Level";
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
        $debitaccount->transactiondate = $transaction;
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
    
    function fullDiscount($refno){
        $duedate = "";
        $idno = "";
        $orno = "";
        $plandiscount = 0;
        $otherdiscount = array();
        
        $credits = DB::Select("Select receiptno,idno,max(duedate) as due from credits where refno = '$refno' and isreverse = '0'");
        
        foreach($credits as $credit){
            $duedate = $credit->due;
            $idno = $credit->idno;
            $orno = $credit->receiptno;
        }
        
        $fulldiscounts = DB::Select("select * from ledgers where idno = '".$idno."' and categoryswitch <= '6' and amount = (plandiscount+otherdiscount) and duedate >= '$duedate' and status =0");
        
        foreach($fulldiscounts as $fulldiscount){
            if($fulldiscount->plandiscount > 0){
                $plandiscount = $plandiscount + $fulldiscount->plandiscount;
            }
            if($fulldiscount->otherdiscount > 0){
                if(array_key_exists($fulldiscount->discountcode, $otherdiscount)){
                    $acctdisc = $otherdiscount [$fulldiscount->discountcode];
                }else{
                    $acctdisc = 0;
                }
                $otherdiscount [$fulldiscount->discountcode]= $acctdisc + $fulldiscount->otherdiscount;
            }
            \App\Ledger::where('id',$fulldiscount->id)->update(['status'=>1]);
            CashierController::credit($idno, $fulldiscount->id, $refno, $orno, $fulldiscount->amount);
        }
        
        if($plandiscount > 0){
            CashierController::debit_reservation_discount($refno, $orno,$idno,env('DEBIT_DISCOUNT') , $plandiscount, "Plan Discount");
        }

        if(count($otherdiscount) > 0){
            foreach($otherdiscount as $key =>$amount){
              CashierController::debit_reservation_discount($refno, $orno,$idno,env('DEBIT_DISCOUNT') , $amount, $key);    
            }
        }
    }
    
    function updateunearned(){
        $entries = array('1158f6ca0d3711e','1158f7069eb3fc2','1158f6e65459e8f','1158f6bf53987ec','1158f6d3e1b192f','1158f707b3246f4','1158f711b148595','1158f6d3e56e244','1158f6d7f27850f','1158f6b00b7bdc2','1158f6b0d3abe19','1158f6feb359649','1158f6adc5ec0a3','1158f6c1eec9dad','1158f6b9a17e56c','1158f6b9f2d5efd','1158f6d47699894','1158f6d4e1e287c','1158f6aea140d0b','1158f6aedf9a7ff','1158f6dbec52421','1158f6be57d0939','1158f6d825f27ed','1158f6b1e8271d2','1158f6c4f01305c','1158f6ce31985b8','1158f6c6b8b5dc0','1158f6f4b46e899','1158f6ae5f31146','1158f6b93a00996','1158f6c02f030e8','1158f6d34c21081','1158f6b0b409259','1158f6d14bcd1a8','1158f6d33a60d34','1158f6d6890f4c6','1158f6b188d7c01','1158f6b19bcc19d','1158f6b227c38dd','1158f6be9dcdd28','1158f6c4637bee5','1158f6c613b9275','1158f6c81257b53','1158f6cee08bab6','1158f70821b8369','1158f6c09349d93','1158f6c5cc883d8','1158f6e1fa47854','1158f6b2fe0a277','1158f6ba53a330e','1158f6f93503b34','1158f6d8ee50485','1158f6edc2ef9e5','1158f6fabba289f','1158f70639a51a2','1158f6cd3c8ddf6','1158f6e7cfe441d','1158f7145b5f638','1158f704ffddd26','1158f705badf164','1158f6dab51bdcc','1158f6adc8e7748','1158f6f412c542f','1158f6badf42b3a','1158f6f26f277b8','1158f6f77869a30','1158f6f7d87979d','1158f6f89754c21','1158f6d47180222','1158f6d48c1833c','1158f6d5ead9308','1158f6cf9a44e6b','1158f6bd57a6714','1158f6b7ec16c62','1158f6d27e3c4ee','1158f6d2a5953d8','1158f6b012e883f','1158f6e0de9fb55','1158f6d6baa8158','1158f6c22c1ab5f','1158f6c2472445d','1158f6cda4c4711','1158f6d730bbc7b','1158f6b79229804','1158f6bedf6afd1','1158f6dd15cf8f9','1158f6e74d422f3','1158f718b5afd22','1158f701921b968','1158f6af3dc90a5','1158f6c7837282d','1158f716ad2339b','1158f6ddcc3ba58','1158f6b6cf70117','1158f6c57688a0e','1158f6f36bc4409','1158f700e843769','1158f6cc7b510e3','1158f6de59cafaf','1158f6e3c517d01','1158f807ab7a540','1158f8064fccaa3','1158f7f8c69398d','1158f7f958b2a44','1158f822e478258','1158f7fa9e0788c','1158f8226d18ce2','1158f8121963212','1158f80f4cd61b9','1158f811231f246','1158f7f4dd9dc17','1158f7f6698ece9','1158f7f8eaee689','1158f801550ef3d','1158f8018eefc60','1158f81042cf9bd','1158f7f600ca05d','1158f7f7b4108b2','1158f7f80990b67','1158f7f842de037','1158f7f9d14404c','1158f7ff8deb75d','1158f80b4c65a5c','1158f80e97e107b','1158f7f467269ab','1158f7fc73bcfe2','1158f7fe710e49c','1158f806ea85d45','1158f80bf7e6eda','1158f80ee566660','1158f8137e5f568','1158f815f25ef8f','1158f8182f5059a','1158f7fddd58533','1158f814a13ce25','1158f7fd5522db6','1158f80d2d87dfd','1158f816d5bfd0b','1158f8195cb0aab','1158f8201b68859','1158f7f5860acf8','1158f803d4e6709','1158f812904bd0d','1158f80e08e964a','1158f81d84f3240','1158f800ec1d803','1158f7fab47f056','1158f7fec72ddc9','1158f8140958b53','1158f7f6b8adc4a','1158f8236c8456e','1158f8171888442','1158f81c0dd1359','1158f821fa71a03');
        
        foreach($entries as $entry){
            $credit = \App\Credit::where('refno',$entry)->first();
            self::setuptuitionfee($entry,$credit->idno,'2017');
        }
    }
    
    function setuptuitionfee($refno,$idno,$schoolyear){
        $entry_type = \App\CtrAutoEntry::where('entry_type',1)->first();
        
        
        //$tuition = \App\Ledger::select('sum(amount) as amount')->where('accountingcode',$entry_type->indic)->where('idno',$idno)->where('schoolyear',$schoolyear)->first();
        $tuitions = DB::Select("Select sum(amount) as amount from ledgers where accountingcode = ".$entry_type->indic." and idno  = '".$idno."' and schoolyear = '".$schoolyear."'");
        
        $amount = 0;
        foreach($tuitions as $tuition){
            $amount = $amount + $tuition->amount;
        }
        
        $credit = \App\Credit::where('refno',$refno)->first();
        $credit->amount = $amount;
        $credit->save();
        
        $debit = \App\Dedit::where('refno',$refno)->first();
        $debit->amount = $amount;
        $debit->save();
        
        
        return null;
    }
    
}
