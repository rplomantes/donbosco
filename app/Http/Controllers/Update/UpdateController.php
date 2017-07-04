<?php

namespace App\Http\Controllers\Update;

use Illuminate\Http\Request;
//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateController extends Controller
{
    function updateDiscount(){
        $discounts = DB::Select("SELECT * FROM  `discounts` WHERE  `description` !=  '3rd Sibling' AND  `schoolyear` LIKE  '2017' and tuitionfee = 100 GROUP BY idno");
        
        foreach ($discounts as $discount){
            if($discount->tuitionfee > 0){
                $disc = $this->makecredit($discount->idno,120100,$discount->discountcode);
                echo $discount->idno." - ".$disc." - ".$discount->discountcode."<br>";
            }

        }
        
        return null;
    }
    
    function makecredit($idno,$acctcode,$discountcode){
        $ledgers = DB::Select("Select * from ledgers where idno = '$idno' and accountingcode = '$acctcode' and schoolyear= '2017' and discountcode = '$discountcode' and amount-otherdiscount = 0"); 
        $creds = \App\Credit::where('idno',$idno)->where('schoolyear','2017')->where('accountingcode','420400')->orderBy('id','desc')->first();
        if(count($creds)>0){
            $student = \App\Dedit::where('refno',$creds->refno)->first();
            $discount = 0;
            foreach($ledgers as $ledger){
                $discount = $discount + $ledger->amount;

                $credit = new \App\Credit;
                   $credit->idno=$idno;
                   $credit->transactiondate = $creds->transactiondate;
                   $credit->referenceid = $ledger->id;
                   $credit->refno = $creds->refno;
                   $credit->receiptno = $creds->receiptno;
                   $credit->accountingcode = $ledger->accountingcode;
                   $credit->categoryswitch = $ledger->categoryswitch;
                   $credit->acctcode = $ledger->acctcode;
                   $credit->description = $ledger->description;
                   $credit->receipt_details = $ledger->receipt_details;
                   $credit->duedate=$ledger->duedate;
                   $credit->amount=$ledger->amount;
                   $credit->entry_type='1';
                   $credit->acct_department=$ledger->acct_department;
                   $credit->sub_department=$ledger->sub_department;
                   $credit->fiscalyear=2016;
                   $credit->schoolyear=$ledger->schoolyear;
                   $credit->period=$ledger->period;
                   $credit->postedby=$creds->postedby;
                   $credit->save();

            }

                $debit = new \App\Dedit;
                $debit->idno = $idno;
                $debit->transactiondate = $creds->transactiondate;
                $debit->refno = $creds->refno;
                $debit->receiptno = $creds->receiptno;
                $debit->paymenttype= "4";
                $debit->entry_type="1";
            if($discountcode == '004'){
                $debit->acctcode = "Other Employees Benefits";
                $debit->accountingcode='500300';
            }else{
                $debit->acctcode = "Youth Assistance";
                $debit->accountingcode='530200';
            }
                $debit->amount = $discount;
                $debit->receivefrom=$student->receivefrom;
                $debit->remarks=$student->remarks;
                $debit->schoolyear=$creds->schoolyear;
                $debit->fiscalyear=$creds->fiscalyear;
                $debit->postedby= $creds->postedby;
                $debit->save();

                return $discount;
        }else{
            return "No Record";
        }
    }
    
    function updatehsconduct(){
        $quarters = \App\CtrQuarter::first();

        $hsgrades = DB::Select("select * from conduct where SY_EFFECTIVE = '2016' and QTR = $quarters->qtrperiod");
        foreach($hsgrades as $hsgrade){
            $newconduct = new \App\ConductRepo;
            $newconduct->OSR = $hsgrade->COM1;
            $newconduct->DPT = $hsgrade->COM2;
            $newconduct->PTY =$hsgrade->COM3;
            $newconduct->DI = $hsgrade->COM4;
            $newconduct->PG = $hsgrade->COM5;
            $newconduct->SIS = $hsgrade->COM6;
            $newconduct->qtrperiod = $quarters->qtrperiod;
            $newconduct->schoolyear = $hsgrade->SY_EFFECTIVE;
            $newconduct->idno=$hsgrade->SCODE;
            $newconduct->save();
            $this->updateconduct($hsgrade->SCODE, 'OSR', $hsgrade->COM1, $hsgrade->QTR, '2016');
            $this->updateconduct($hsgrade->SCODE, 'DPT', $hsgrade->COM2, $hsgrade->QTR, '2016');
            $this->updateconduct($hsgrade->SCODE, 'PTY', $hsgrade->COM3, $hsgrade->QTR, '2016');
            $this->updateconduct($hsgrade->SCODE, 'DI' , $hsgrade->COM4, $hsgrade->QTR, '2016');
            $this->updateconduct($hsgrade->SCODE, 'PG' , $hsgrade->COM5, $hsgrade->QTR, '2016');
            $this->updateconduct($hsgrade->SCODE, 'SIS', $hsgrade->COM6, $hsgrade->QTR, '2016');
        }

    }
   
    public function updateconduct($idno,$ctype,$cvalue,$qtrperiod,$schoolyear){
          if(strlen($idno)==5){
            $idno = "0".$idno;
        }   
          if(!is_null($cvalue) || $cvalue!=""){  
            switch($qtrperiod){
            case 1:
                $qtrname = "first_grading";
                break;
            case 2:
                $qtrname="second_grading";
                break;
            case 3:
                $qtrname="third_grading";
                break;
            case 4:
                $qtrname="fourth_grading";
        }
        
        $cupate = \App\Grade::where('idno',$idno)->where('subjectcode',$ctype)->where('schoolyear',$schoolyear)->first();
        if(count($cupate)>0){
        $cupate->$qtrname=$cvalue;
        $cupate->update();
        }
          }
        }
        
        public function updatehsgrade(){
            $sy = \App\ctrSchoolYear::first();
            $quarters = \App\CtrQuarter::first();
            //$grades = DB::connection('dbti2prod')->Select("select * from grade where SY_EFFECTIVE = $sy->schoolyear and QTR = $quarters->qtrperiod");
            $grades = DB::Select("select * from grade where SY_EFFECTIVE = $sy->schoolyear and QTR = $quarters->qtrperiod");
            foreach($grades as $grade){
             $this->updatehs($grade->SCODE, $grade->SUBJ_CODE, $grade->GRADE_PASS1, $grade->QTR);
            }
        }
        
        public function updatehs($idno, $subjectcode, $grade,$qtrperiod){
            switch($qtrperiod){
            case 1:
                $qtrname = "first_grading";
                break;
            case 2:
                $qtrname="second_grading";
                break;
            case 3:
                $qtrname="third_grading";
                break;
            case 4:
                $qtrname="fourth_grading";
                
        }
        //$check = \App\Status::where('idno',$idno)->where('department','Junior High School')->first();
        $check = \App\Status::where('idno',$idno)->first();
        //if(count($check) != 0 && $subjectcode =='MAPEH'){
        $withrecord = \App\SubjectRepo::where('qtrperiod',$qtrperiod)->where('idno',$idno)->where('subjectcode',$subjectcode)->exists();
        if(!$withrecord){
            if(count($check) != 0 ){
            $newgrade = \App\Grade::where('idno',$idno)->where('subjectcode',$subjectcode)->first();

            if(count($newgrade)>0){
            $newgrade->$qtrname=$grade;
            $newgrade->update();
            }
            $loadgrade = new \App\SubjectRepo;
            $loadgrade->idno=$idno;
            $loadgrade->subjectcode=$subjectcode;
            $loadgrade->grade=$grade;
            $loadgrade->qtrperiod=$qtrperiod;
            $loadgrade->schoolyear='2016';
            $loadgrade->save();
            }
            }
        }
        function checkno(){
            $idnos=DB::Select("select * from grade2");
            return view('checkno',compact('idnos'));
        }
        public function updatehsattendance(){
            $dayahs = DB::Select("Select * from grade2 where SUBJ_CODE = 'DAYA'");
            foreach($dayahs as $daya){
                $updayp = \App\Grade::where('idno',$daya->SCODE)->where('subjectcode','DAYP')->first();
               if(count($updayp)>0){
                $updayp->first_grading = 48 - $daya->GRADE_PASS1;
                $updayp->update();
               }
            }
        }
        
        function updateacctcode(){
            $updatedbs = DB::Select("select * from crsmodification");
            foreach($updatedbs as $updatedb){
                $updatecrs = \App\Credit::where('receipt_details',$updatedb->receipt_details)->get();
                foreach($updatecrs as $updatecr){
                    $crs = \App\Credit::find($updatecr->id);
                    $crs->acctcode = $updatedb->acctcode;
                    $crs->update();
                }
                
            }
            return "Done";
        }
        
        function updatecashdiscount(){
            $cashdiscounts = \App\Dedit::where('paymenttype','4')->get();
            if(count($cashdiscounts)>0){
                foreach($cashdiscounts as $cashdiscount){
                    $discountname = \App\Discount::where('idno',$cashdiscount->idno)->first();
                    $dname="Plan Discount";
                     if(count($discountname)>0){
                     $dname = $discountname->description;
                    }
                    $cashdiscount->acctcode = $dname;
                    $cashdiscount->update();
                }
                return "done updating";
            }
        }
        function prevgrade(){
            $sy = "2013";
            $students = DB::connection('dbti2test')->select("select distinct scode from grade_report where SY_EFFECTIVE = '$sy'");
            
            foreach($students as $student){
                $newstudents = \App\User::where('idno',$student->scode)->first();
                if(count($newstudents)>0){
                    $this->migrategrade($student->scode,$sy);
                }
            }
            //$this->migrategrade("021067",$sy);
        }
        function migrategrade($scode,$sy){
                
                do{
                    if(strlen($scode) < 6){
                        $scode = "0".$scode;
                    }
                }while(strlen($scode) < 6);
              
            $hsgrades = DB::connection('dbti2test')->select("select * from grade_report "
                    . "where SY_EFFECTIVE = '$sy'"
                    . "and SCODE =".$scode);

            foreach($hsgrades as $grade){
                if($grade->GR_YR == 'I'){
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CL,"CL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SC,"SC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->PE,"PE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MUS,"MUS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MATH,"MATH");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->GMRC,"GMRC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->FIL,"FIL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ENGL,"ENGL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->COM1,"COM1");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->AP,"AP");//
                }
                else if($grade->GR_YR == 'II'){
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CL,"CL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ART,"ART");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SC,"SC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->PE,"PE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MUS,"MUS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MATH,"MATH");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->GMRC,"GMRC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->FIL,"FIL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ENGL,"ENGL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->COM1,"COM1");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->AP,"AP");//
                }
                else if($grade->GR_YR == 'III'){
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CL,"CL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ART,"ART");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SC,"SC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->PE,"PE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MUS,"MUS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MATH,"MATH");
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->GMRC,"GMRC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->FIL,"FIL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ENGL,"ENGL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->COM1,"COM1");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->AP,"AP");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->WORK,"WORK");//
                }
                else if($grade->GR_YR == 'IV'){
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CL,"CL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ART,"ART");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SC,"SC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->PE,"PE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MUS,"MUS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MATH,"MATH");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->HEK,"HEK");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->GMRC,"GMRC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->FIL,"FIL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ENGL,"ENGL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->COM1,"COM1");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->WORK,"WORK");//
                }
                else if($grade->GR_YR == 'V'){
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CL,"CL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ART,"ART");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SC,"SC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->PE,"PE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MUS,"MUS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MATH,"MATH");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->HEK,"HEK");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->GMRC,"GMRC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->FIL,"FIL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ENGL,"ENGL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->COM1,"COM1");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->WORK,"WORK");//
                }
                else if($grade->GR_YR == 'VI'){
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CL,"CL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ART,"ART");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SC,"SC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->PE,"PE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MUS,"MUS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MATH,"MATH");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->HEK,"HEK");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->GMRC,"GMRC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->FIL,"FIL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ENGL,"ENGL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->COM1,"COM1");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->WORK,"WORK");//
                }
                else if($grade->GR_YR == 1){
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->eTEX,"eTEX");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->AMT,"AMT");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->DT,"DT");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CT,"CT");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->AP,"AP");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->VE,"VE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SC,"SC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->RHGP,"RHGP");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->PE,"PE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MUS,"MUS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MATH,"MATH");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->GMRC,"GMRC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->FIL,"FIL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ENGL,"ENGL");//
                }            
                else if($grade->GR_YR == 2){
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->AMT,"AMT");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->eTEX,"eTEX");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CT,"CT");//   
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->AP,"AP");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->VE,"VE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SC,"SC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->RHGP,"RHGP");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->PE,"PE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MUS,"MUS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MATH,"MATH");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->GMRC,"GMRC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->FIL,"FIL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ENGL,"ENGL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->DT,"DT");
                }            
                else if($grade->GR_YR == 3){
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->eTEX,"eTEX");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->AMT,"AMT");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->DT,"DT");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CT,"CT");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CADD,"CADD");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->FIL,"FIL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->AP,"AP");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->VE,"VE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SC,"SC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->RHGP,"RHGP");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ENGL,"ENGL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->PE,"PE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MUS,"MUS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->GMRC,"GMRC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MATH,"MATH");//
                }            
                else if($grade->GR_YR == 4){
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CADD,"CADD");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ALGEB,"ALGEB");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MUS,"MUS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->RHGP,"RHGP");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SC,"SC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SHOP,"SHOP");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->TECH,"TECH");
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->MATH,"MATH");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->HPE,"H&PE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->GMRC,"GMRC");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->FIL,"FIL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->ENGL,"ENGL");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->CAT,"CAT");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->VE,"VE");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->SS,"SS");//
                    $this->savegrade($scode,$sy,$grade->QTR,$grade->GR_YR,$grade->SECTION,$grade->TRIGO,"TRIGO");//
                }                    
            }
            
        }
        
        function savegrade($scode,$sy,$qtr,$level,$section,$score,$subj){
                $check = $this->check($scode,$subj,$sy);
                if(empty($check)){
                    $subjects = DB::connection('dbti2test')->select("Select subj_card,class from subject_updated where subj_code = '$subj'");
                    $orders = DB::connection('dbti2test')->select("Select hs_subj_order,gs_subj_order from subject where subj_code = '$subj'");
                    $record = new \App\Grade();
                    $record->idno = $scode;
                    $record->level = $this->changegrade($level);
                    $record->subjectcode = $subj;
                    $record->section = $section;
                    
                    if($level == 1 ||$level == 2||$level == 3||$level == 4){
                        foreach($orders as $order){
                            $record->sortto = $order->hs_subj_order;
                        }
                    }else{
                        foreach($orders as $order){
                            $record->sortto = $order->gs_subj_order;
                        }
                    }
                    
                    foreach($subjects as $subject){
                    $record->subjectname = $subject->subj_card;
                    }
                    $record->subjecttype = $this->settype($subject->class);
                    if($qtr == 1){
                        $record->first_grading = $score;
                    }else if($qtr == 2){
                        $record->second_grading = $score;
                    }else if($qtr == 3){
                        $record->third_grading = $score;
                    }else if($qtr == 4){
                        $record->fourth_grading = $score;
                    }  
                    $record->schoolyear = $sy;
                    $record->save();
                }else{
                    $record = \App\Grade::where('idno',$scode)->where('subjectcode',$subj)->where('schoolyear',$sy)->first();
                    if($qtr == 1){
                        $record->first_grading = $score;
                    }else if($qtr == 2){
                        $record->second_grading = $score;
                    }else if($qtr == 3){
                        $record->third_grading = $score;
                    }else if($qtr == 4){
                        $record->fourth_grading = $score;
                    }        
                    $record->save();
                }
        }
        
        function settype($subjcode){
            $code = 4;
            if($subjcode == 'A'){
                $code = 0;
            }
            if($subjcode == 'T'){
                $code = 1;
            }
            if($subjcode == 'C'){
                $code = 3;
            }
            
            return $code;
        }
        
        function check($scode,$subj,$sy){
            $result = \App\Grade::where('idno',$scode)->where('subjectcode',$subj)->where('schoolyear',$sy)->first();
            
            return $result;
        }
        
        function changegrade($level){
            if($level == 'I'){
                $newlevel = "Grade 1";
            }
            else if($level == 'II'){
                $newlevel = "Grade 2";
            }
            else if($level == 'III'){
                $newlevel = "Grade 3";
            }
            else if($level == 'IV'){
                $newlevel = "Grade 4";
            }
            else if($level == 'V'){
                $newlevel = "Grade 5";
            }
            else if($level == 'VI'){
                $newlevel = "Grade 6";
            }
            else if($level == 1){
                $newlevel = "Grade 7";
            }            
            else if($level == 2){
                $newlevel = "Grade 8";
            }            
            else if($level == 3){
                $newlevel = "Grade 9";
            }            
            else if($level == 4){
                $newlevel = "Grade 10";
            }
            
            return $newlevel;
        }
        function makepaymentschedule(Request $request){
            //return $request->level;
        
            $departments = \App\CtrFee::where('level',$request->level)->first();
            $department = $departments->department;
            $schoolyear = \App\ctrSchoolYear::where('department',$department)->first();
            $schedules = \App\CtrRefSchedule::where('plan',$request->plan)->get();
            $firstsched =true;
            $count = 1;
            foreach($schedules as $schedule){
               if($schedule->duetype == "1"){
                    $installments= \App\CtrRefInstallment::where('plan', $request->plan)->get();
                     
                    foreach($installments as $installment){
                      
                      if($request->strand != null){  
                      $paymentschedules = \App\CtrFee::where('level',$request->level)->where('acctcode',$schedule->acctcode)->where('strand',$request->strand)->get();
                      }else{
                        
                      $paymentschedules = \App\CtrFee::where('level',$request->level)->where('acctcode',$schedule->acctcode)->get();
                          
                      }
                       
                        foreach($paymentschedules as $paymentschedule){
                        $newsched = new \App\CtrPaymentSchedule;
                        $newsched->plan = $schedule->plan;
                        $newsched->department = $paymentschedule->department;
                        $newsched->level = $paymentschedule->level;
                        $newsched->strand = $paymentschedule->strand;
                        $newsched->course = $paymentschedule->course;
                        $newsched->categoryswitch = $paymentschedule->categoryswitch;
                        $newsched->accountingcode = $paymentschedule->acctcode;
                        $newsched->acctcode = $paymentschedule->acctname;
                        $newsched->description = $paymentschedule->subsidiary;
                        $newsched->receipt_details = $paymentschedule->acctname;
                        if($request->plan == "Quarterly" && $paymentschedule->acctcode =="110100"){
                            if($count % 2 == 0){
                                $newsched->amount= round($paymentschedule->amount*.2,2);
                            }else{
                                $newsched->amount= round($paymentschedule->amount*.3,2);
                            }
                            
                        }
                        else{
                            $newsched->amount= round($paymentschedule->amount/count($installments),2);
                        }
                        
                        if($request->plan == "Semi Annual" && $paymentschedule->acctcode =="110100"){
                            if($firstsched){
                                $newsched->discount = round(($paymentschedule->amount/count($installments)) * .015 ,2);
                                $firstsched=false;
                            }
                        }
                        $newsched->sub_department= $paymentschedule->sub_department;
                        $newsched->acct_department = $paymentschedule->acct_department;
                        $newsched->schoolyear = $schoolyear->schoolyear;
                        $newsched->duetype = $installment->duetype;
                        $newsched->duedate = $installment->duedate;
                        $newsched->save();
                        
                    }
                        $count++;
                    }
                    
                    
                }
                elseif($schedule->duetype=='0'){
                    if($request->strand != null){  
                      $paymentschedules = \App\CtrFee::where('level',$request->level)->where('acctcode',$schedule->acctcode)->where('strand',$request->strand)->get();
                      }else{ 
                      $paymentschedules = \App\CtrFee::where('level',$request->level)->where('acctcode',$schedule->acctcode)->get();
                      }
                    foreach($paymentschedules as $paymentschedule){
                        $newsched = new \App\CtrPaymentSchedule;
                        $newsched->plan = $schedule->plan;
                        $newsched->department = $paymentschedule->department;
                        $newsched->level = $paymentschedule->level;
                        $newsched->strand = $paymentschedule->strand;
                        $newsched->course = $paymentschedule->course;
                        $newsched->categoryswitch = $paymentschedule->categoryswitch;
                        $newsched->accountingcode = $paymentschedule->acctcode;
                        $newsched->acctcode = $paymentschedule->acctname;
                        $newsched->description = $paymentschedule->subsidiary;
                        $newsched->receipt_details = $paymentschedule->acctname;
                        $newsched->amount= $paymentschedule->amount;
                            if($request->plan == "Annual" && $paymentschedule->acctcode =="110100"){
                                $newsched->discount = round($paymentschedule->amount * .03,2);
                            }
                        $newsched->sub_department=$paymentschedule->sub_department;
                        $newsched->acct_department=$paymentschedule->acct_department;    
                        $newsched->schoolyear = $schoolyear->schoolyear;
                        $newsched->duetype = "0";
                        $newsched->duedate = "2017-04-01";
                        $newsched->save();
                        
                    }
                }
                //end of due type 0
            }
        }
        function updateentrytype(){
            $string="hello";
        $totaldebit = DB::Select("select distinct refno from dedits where paymenttype = '1'");
        foreach($totaldebit as $tb){
            \App\Dedit::where('refno',$tb->refno)->update(['entry_type'=>'1']);
        }
        
            return "done";
        }
        function updatedmtoaccounting(){
            $credits = \App\Credit::where('entry_type','2')->get();
            foreach($credits as $credit){
                $addcredit = new \App\Accounting;
                $addcredit->refno = $credit->refno;
                $addcredit->referenceid = $credit->receiptno;
                $addcredit->transactiondate = $credit->transactiondate;
                $addcredit->accountname = $credit->acctcode;
                $addcredit->accountcode = $credit->accountingcode;
                $addcredit->subsidiary = $credit->description;
                $addcredit->sub_department = $credit->sub_department;
                $addcredit->acct_department = $credit->acct_department;
                $addcredit->credit = $credit->amount;
                $addcredit->fiscalyear=$credit->fiscalyear;
                $addcredit->isreversed=$credit->isreverse;
                $addcredit->posted_by = $credit->postedby;
                $addcredit->cr_db_indic = "1";
                $addcredit->isfinal = "1";
                $addcredit->type="2";
                $addcredit->save();
            }
            $credits = \App\Dedit::where('entry_type','2')->get();
            foreach($credits as $credit){
                $addcredit = new \App\Accounting;
                $addcredit->refno = $credit->refno;
                $addcredit->referenceid = $credit->receiptno;
                $addcredit->transactiondate = $credit->transactiondate;
                $addcredit->accountname = $credit->acctcode;
                $addcredit->accountcode = $credit->accountingcode;
                $addcredit->subsidiary = $credit->description;
                $addcredit->sub_department = $credit->sub_department;
                $addcredit->acct_department = $credit->acct_department;
                $addcredit->debit = $credit->amount;
                $addcredit->isreversed=$credit->isreverse;
                $addcredit->fiscalyear=$credit->fiscalyear;
                $addcredit->posted_by = $credit->postedby;
                $addcredit->cr_db_indic = "0";
                $addcredit->isfinal = "1";
                $addcredit->type="2";
                $addcredit->save();
            }
            return "Done";
        }
        
        function updatedebitmemo(){
            $debits = DB::Select("Select idno, transactiondate, refno,  isreverse, "
                    . "remarks, fiscalyear, schoolyear, postedby, receivefrom, entry_type,  sum(amount) as amount "
                    . "from dedits group by  idno, transactiondate, refno, receiptno, "
                    . "remarks, fiscalyear, schoolyear, postedby, receivefrom, isreverse, entry_type having entry_type = '2'");
            foreach($debits as $debit){
            $adddm = new \App\DebitMemo;
            $adddm->fullname = $debit->receivefrom;
            $adddm->idno = $debit->idno;
            $adddm->refno = $debit->refno;
            $adddm->transactiondate = $debit->transactiondate;
            $adddm->amount = $debit->amount;
            $adddm->remarks=$debit->remarks;
            $adddm->isreverse = $debit->isreverse;
            $adddm->fiscalyear = $debit->fiscalyear;
            $adddm->schoolyear = $debit->schoolyear;
            $adddm->postedby = $debit->postedby;
            $adddm->save();
            }        
            return "Done DM";
        }
        function updatecdb(){
            
            $forwardeds = \App\ForwardedCbd::get();
            
            foreach($forwardeds as $forwarded){
                $entrycode = \App\UploadCbccode::where('accountname',$forwarded->accounttitle)->first();
                //$entryreturn = $entrycode->accountcode;
                if(count($entrycode)>0){
                $forwarded->acctcode = $entrycode->accountcode;
                $forwarded->update();  
                }
               // $testing = \App\UploadCbccode::get();
            }
            return "done";
        }
        
        function updatecdbdepartment(){
            $departments = \App\ForwardedCbd::get();
            foreach($departments as $department){
                $department_cdb = \App\DepartmentCdb::where('department',$department->UNIT_NAME)->first();
                if(count($department_cdb)>0){
                $department->acct_department = $department_cdb->acct_department;
                $department->sub_department = $department_cdb->sub_department;
                $department->update(); 
                }else{
                $department->acct_department = "None";
                $department->sub_department = "None";
                $department->update();
                }
            }
            return "done";
        }
        function updatecdbaccountname(){
            $cdbs = \App\ForwardedCbd::get();
            $notncluded="";
            foreach($cdbs as $cdb){
                $fromchart = \App\ChartOfAccount::where('acctcode',$cdb->acctcode)->first();
                if(count($fromchart)>0){
                $cdb->acctname = $fromchart->accountname;
                $cdb->update();
                }else
                {
                   $notncluded = $notncluded. " " . $cdb->acctcode; 
                }
            }
            return $notncluded;
        }
        
        function updatecdbmain(){
            $populates = \App\ForwardedCbd::where('acctcode','<','110030')->where('acctcode','>','110010')->where('DEBIT','false')->get();
            foreach($populates as $populate){
                $newd = new \App\Disbursement;
                $newd->transactiondate = $populate->TR_DATE;
                $newd->bank=$populate->acctname;
                $newd->refno = $populate->VOUCHER_NO;
                $newd->payee = $populate->PAYEE;
                $newd->voucherno = $populate->VOUCHER_NO;
                $newd->checkno = $populate->BANK_CHECK_NO;
                $newd->amount = $populate->ACCOUNT_AMOUNT;
                $newd->remarks = $populate->EXPLANATION;
                $newd->postedby = "larabelle";
                $newd->save();
            }
        }
            function updatecdbaccounting(){
                 $populates = \App\ForwardedCbd::get();
            foreach($populates as $populate){
                $newacct = new \App\Accounting;
                $newacct->refno = $populate->VOUCHER_NO;
                $newacct->transactiondate = $populate->TR_DATE;
                $newacct->referenceid = $populate->VOUCHER_NO;
                $newacct->accountname = $populate->acctname;
                $newacct->accountcode = $populate->acctcode;
                $newacct->acct_department = $populate->acct_department;
                $newacct->sub_department = $populate->sub_department;
                $newacct->fiscalyear = '2016';
                $newacct->isfinal = '1';
                $newacct->type = '4';
                if($populate->DEBIT == "FALSE"){
                    $newacct->cr_db_indic = '1';
                    $newacct->credit=$populate->ACCOUNT_AMOUNT;
                }else{
                    $newacct->cr_db_indic = '0';
                    $newacct->debit=$populate->ACCOUNT_AMOUNT;
                }
                $newacct->save();
                
            }
            return "Done!!!!!!!!!";
            }
            
            function updatecdbdrcr(){
                $entry_type = "4";
                $updates = \App\Accounting::where('type','4')->get();
                    foreach ($updates as $update){
                        if($update->cr_db_indic == '1'){
                            $add = new \App\Credit;
                            $add->amount = $update->credit;
                        }else{
                            $add = new \App\Dedit;
                            $add->amount=$update->debit;
                        }
                         $add->transactiondate = $update->transactiondate;
                         $add->refno = $update->refno;
                         $add->receiptno = $update->referenceid;
                         $add->accountingcode = $update->accountcode;
                         $add->acctcode = $update->accountname;
                         $add->description = $update->subsidiary;
                         $add->entry_type = $entry_type;
                         $add->acct_department = $update->acct_department;
                         $add->sub_department = $update->sub_department;
                         $add->fiscalyear = $update->fiscalyear;
                         $add->postedby = 'larabelle';
                         $add->save();
                         
                    }
                    
                    return "Finally Done!!!";
            }
                
            
        
        
}
