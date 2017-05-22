<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;

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
            //$credits = \App\Credit::where('accountingcode','440000')->where('idno',$status->idno)->get();
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
       
       $totaldiscelearnig = 0;
       $totaldiscmisc = 0;
       $totaldiscdept = 0;
       $totaldiscreg = 0;
       $totaldisctuition = 0;
       $totaldiscbook = 0;
       
       $totalfapeelearnig = 0;
       $totalfapemisc = 0;
       $totalfapedept = 0;
       $totalfapereg = 0;
       $totalfapetuition = 0;
       $totalfapebook = 0;
       
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
       
       $crossmoney = 0;
       $sy = 2017;
       $receipts = DB::Select("Select distinct refno from statuses s join credits c on s.idno = c.idno and s.schoolyear = c.schoolyear where s.schoolyear = $sy and s.status = 2 and isreverse = 0 and (date_enrolled between '2017-02-01' AND '2017-04-31') and (transactiondate between '2017-02-01' AND '2017-04-31') and accountingcode in (420200,420400,420100,420000,120100,440400) and s.department != 'TVET' and referenceid != ''");
       
       foreach($receipts as $receipt){
           $elearnig = 0;
           $misc = 0;
           $dept = 0;
           $reg = 0;
           $tuition = 0;
           $book = 0;
           
           $discelearnig = 0;
           $discmisc = 0;
           $discdept = 0;
           $discreg = 0;
           $disctuition = 0;
           $discbook = 0;

           $fapeelearnig = 0;
           $fapemisc = 0;
           $fapedept = 0;
           $fapereg = 0;
           $fapetuition = 0;
           $fapebook = 0;

           $reselearnig = 0;
           $resmisc = 0;
           $resdept = 0;
           $resreg = 0;
           $restuition = 0;
           $resbook = 0;

           $depelearnig = 0;
           $depmisc = 0;
           $depdept = 0;
           $depreg = 0;
           $deptuition = 0;
           $depbook = 0;
           
           $summoneys = DB::Select("Select sum(amount)+sum(checkamount) as money from dedits where refno = $receipt->refno and paymenttype IN (1,5)");
           foreach($summoneys as $summoney){
               $money = $summoney->money;
           }
           $crossmoney = $crossmoney + $money;
           $accounts = \App\Credit::where('refno',$receipt->refno)->get();
           
           $divider = DB::Select("Select * from credits where refno = $receipt->refno group by accountingcode");
           $noncashes = \App\Dedit::where('refno',$receipt->refno)->whereIn('paymenttype',array(2,3,4,6,7,8,9))->get();
           
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
           }
           
           foreach($noncashes as $noncash){
               if($noncash->accountingcode == 410100 || $noncash->accountingcode == 410200){
                   $tuition = $tuition - $account->amount;
                   
               }
               if($noncash->description == 'FAPE'){
                   $divideto = $account->amount/count($divider);
                    $elearnig = $elearnig - $divideto;
                    $misc = $misc - $divideto;
                    $dept = $dept - $divideto;
                    $reg = $reg - $divideto;
                    $tuition = $tuition - $divideto;
                    $book = $book - $divideto;
               }
               if($noncash->paymenttype == 4 && ($noncash->accountingcode != 410100 || $noncash->accountingcode != 410200)){
                   foreach($accounts as $account){
                       $ledger = \App\Ledger::where('id',$account->referenceid)->first();
                       if(count($ledger)>0){
                           
                        if($ledger->amount - ($ledger->plandiscount+$ledger->otherdiscount)==0){
                            $minus = $ledger->otherdiscount;
                        }else{
                            $minus = 0;
                        }

                         if($ledger->accountingcode == 420200){
                             $elearnig = $elearnig + $ledger->otherdiscount;
                         }
                         if($ledger->accountingcode == 420400){
                             $misc = $misc + $ledger->otherdiscount;
                         }
                         if($ledger->accountingcode == 420100){
                             $dept = $dept + $ledger->otherdiscount;
                         }
                         if($ledger->accountingcode == 420000){
                             $reg = $reg + $ledger->otherdiscount;
                         }
                         if($ledger->accountingcode == 120100){
                             $tuition = $tuition + $ledger->otherdiscount;
                         }
                         if($ledger->accountingcode == 440400){
                             $book = $book + $ledger->otherdiscount;
                         }
                       }
                   }
               }
           
                    $totalelearnig = $totalelearnig + $elearnig;
                    $totalmisc = $totalmisc + $misc;
                    $totaldept = $totaldept + $dept;
                    $totalreg = $totalreg + $reg;
                    $totaltuition = $totaltuition + $tuition;
                    $totalbook = $totalbook + $book;
               
               }
           
           
           
       }
       echo $totalelearnig."<br>";
       echo $totalmisc."<br>";
       echo $totaldept."<br>";
       echo $totalreg."<br>";
       echo $totaltuition."<br>";
       echo $totalbook."<br>";
       echo "<br>".$crossmoney."<br>";
       return "none";
       
   }
}
