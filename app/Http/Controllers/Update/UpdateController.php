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

        $hsgrades = DB::Select("select * from JHSconduct2017_2nd where SY_EFFECTIVE = '2017' and QTR = $quarters->qtrperiod");
        foreach($hsgrades as $hsgrade){
            $newconduct = new \App\ConductRepo;
            $newconduct->OSR = $hsgrade->obedience;
            $newconduct->DPT = $hsgrade->deportment;
            $newconduct->PTY =$hsgrade->piety;
            $newconduct->DI = $hsgrade->diligence;
            $newconduct->PG = $hsgrade->positive;
            $newconduct->SIS = $hsgrade->sociability;
            $newconduct->qtrperiod = $quarters->qtrperiod;
            $newconduct->schoolyear = $hsgrade->SY_EFFECTIVE;
            $newconduct->idno=$hsgrade->SCODE;
            $newconduct->save();
            $this->updateconduct($hsgrade->SCODE, 'OSR', $hsgrade->obedience, $hsgrade->QTR, '2017');
            $this->updateconduct($hsgrade->SCODE, 'DPT', $hsgrade->deportment, $hsgrade->QTR, '2017');
            $this->updateconduct($hsgrade->SCODE, 'PTY', $hsgrade->piety, $hsgrade->QTR, '2017');
            $this->updateconduct($hsgrade->SCODE, 'DI' , $hsgrade->diligence, $hsgrade->QTR, '2017');
            $this->updateconduct($hsgrade->SCODE, 'PG' , $hsgrade->positive, $hsgrade->QTR, '2017');
            $this->updateconduct($hsgrade->SCODE, 'SIS', $hsgrade->sociability, $hsgrade->QTR, '2017');
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
            if($cupate->$qtrname == 0){
        $cupate->$qtrname=$cvalue;
        $cupate->update();
            }
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
            $sy = "2015";
            //$students = DB::connection('dbti2test')->select("select distinct scode from grade_report where SY_EFFECTIVE = '$sy'");
              $students = DB::connection('dbti2test')->select("select distinct scode from grade where SY_EFFECTIVE = '$sy' and SCODE IN (160946,120715,052892,123803,121215,052027,123242,051390,051250,121452,074063,050652,055077,054551,083208,123692,062391,120731,051772,121436,051683,120758,095273,121193,061891,122679,050938,064793,065064,072842,051489,055425,064912,062197,113476,093378,051373,051454,083071,094412,121100,050822,053414,051764,050491,061867,054950,062081,052159,120898,120910,1640011,052132,052191,050423,055000,062316,051268,051144,061905,123552,061948,095966,120987,121533,122491,051641,051594,1640026,1640032,050661,120839,061662,051462,073300,120651,065226,1640136,050431,050300,120871,1610386,052299,082511,074250,160628,063002,123676,061816,103861,052019,053988,074055,064122,122106,050911,1640273,053015,063312,035882,160997,123731,061697,121177,123943,050881,054003,122505,053651,121495,125405,051519,1640430,120693,050466,056146,1610302,123722,052388,103047,050628,1610083,1640507,065986,122556,053333,062448,121037,1692657,050598,051471,051691,120685,061964,094382,051551,051845,051021,1640602,1693452,1640671,1640686,1640692,064955,083186,051837,062278,072664,053121,121878,1640760,053457,050474,062022,053473,120845,102989,050849,1640555,063983,1640885,052990,1684447,053317,160989,1640980,053538,050377,121461,073296,092118,123269,055522,1600164,1640294,101991,122521,1640403,160776,121291,054071,055387,085073,160661,1690535,161136,161047,160610,160598,052973,064653,053490,051748,161594,161624,160971,160814,160636,161101,160644,062162,054429,160679,161021,160652,122696,160792,161055,053279,161071,160954,1641151,1641166,064009,160806,160687,061735,050555,055069,052337,1641193,123625,064742,061727,1640445,050407,1621423,053619,082708,065170,053511,1690624,122611,075213,054135,1640424,1620881,1641187,123561,062359,122777,074331,121525,1641412,084965,054631,052051,052221,062103,062235,1601428,050156,121134,065196,120928,075311,074926,074811,052906,050725,101907,1621471,061751,083500,050580,122149,062201,066419,053350,124265,055450)");
            ini_set("memory_limit","850M"); 
            set_time_limit('1000'); 
            
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
            $level = "";
            $section = "";
            $infos = DB::connection('dbti2test')->select("select * from grade_report "
                    . "where SY_EFFECTIVE = '$sy'"
                    . "and SCODE =".$scode);
            foreach($infos as $info){
                $level = $info->GR_YR;
                $section = $info->SECTION;
            }
            $hsgrades = DB::connection('dbti2test')->select("select * from grade "
                    . "where SY_EFFECTIVE = '$sy'"
                    . "and SCODE =".$scode);

            foreach($hsgrades as $grade){
                $this->savegrade($scode,$sy,$grade->QTR,$level,$section,$grade->GRADE_PASS1,$grade->SUBJ_CODE);
            }
            /*
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
            */
        }
        
        function savegrade($scode,$sy,$qtr,$level,$section,$score,$subj){
                $check = $this->check($scode,$subj,$sy);
                if(empty($check)){
                    $subjects = DB::connection('dbti2test')->select("Select subj_card,class from subject_updated where subj_code = '$subj'");
                    $orders = DB::connection('dbti2test')->select("Select hs_subj_order,gs_subj_order,wtd_val_ave from subject where subj_code = '$subj'");
                    
                    
                    $record = new \App\Grade();
                    $record->idno = $scode;
                    $record->level = $this->changegrade($level);
                    $record->subjectcode = $subj;
                    $record->section = $section;
                    
                    //if($level == 1 ||$level == 2||$level == 3||$level == 4){
                    if($level == 7 ||$level == 8||$level == 9||$level == 10){
                        foreach($orders as $order){
                            $record->sortto = $order->hs_subj_order;
                            $record->weighted = $order->wtd_val_ave;
                        }
                    }else{
                        foreach($orders as $order){
                            $record->sortto = $order->gs_subj_order;
                        }
                    }
                    
                    foreach($subjects as $subject){
                    $record->subjectname = $subject->subj_card;
                    $record->subjecttype = $this->settype($subject->class);
                    }
                    
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
                }
                else{
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
            $code = 3;
            if($subjcode == 'A'){
                $code = 0;
            }
            if($subjcode == 'T'){
                $code = 1;
            }
            if($subjcode == 'C'){
                $code = 2;
            }
            
            return $code;
        }
        
        function check($scode,$subj,$sy){
            $result = \App\Grade::where('idno',$scode)->where('subjectcode',$subj)->where('schoolyear',$sy)->first();
            
            return $result;
        }
        
        function changegrade($level){
            $newlevel = "";
//            if($level == 'I'){
//                $newlevel = "Grade 1";
//            }
//            else if($level == 'II'){
//                $newlevel = "Grade 2";
//            }
//            else if($level == 'III'){
//                $newlevel = "Grade 3";
//            }
//            else if($level == 'IV'){
//                $newlevel = "Grade 4";
//            }
//            else if($level == 'V'){
//                $newlevel = "Grade 5";
//            }
//            else if($level == 'VI'){
//                $newlevel = "Grade 6";
//            }
//            else if($level == 1){
//                $newlevel = "Grade 7";
//            }            
//            else if($level == 2){
//                $newlevel = "Grade 8";
//            }            
//            else if($level == 3){
//                $newlevel = "Grade 9";
//            }            
//            else if($level == 4){
//                $newlevel = "Grade 10";
//            }
            
            if($level == 1){
                $newlevel = "Grade 1";
            }
            else if($level == 2){
                $newlevel = "Grade 2";
            }
            else if($level == 3){
                $newlevel = "Grade 3";
            }
            else if($level == 4){
                $newlevel = "Grade 4";
            }
            else if($level == 5){
                $newlevel = "Grade 5";
            }
            else if($level == 6){
                $newlevel = "Grade 6";
            }
            else if($level == 7){
                $newlevel = "Grade 7";
            }            
            else if($level == 8){
                $newlevel = "Grade 8";
            }            
            else if($level == 9){
                $newlevel = "Grade 9";
            }            
            else if($level == 10){
                $newlevel = "Grade 10";
            }
            else if($level == "K"){
                $newlevel = "Kindergarten";
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
                 //$populates = \App\ForwardedCbd::get();
//                $populates = DB::Select("Select * from journal_old");
//            foreach($populates as $populate){
//                $newacct = new \App\Accounting;
//                $newacct->refno = "j".$populate->VOUCHER_NO;
//                $newacct->transactiondate = $populate->TR_DATE;
//                $newacct->referenceid = $populate->VOUCHER_NO;
//                $newacct->accountname = $populate->accountingname;
//                $newacct->accountcode = $populate->accountingcode;
//                $newacct->acct_department = $populate->main_department;
//                $newacct->sub_department = $populate->sub_department;
//                $newacct->fiscalyear = '2016';
//                $newacct->isfinal = '1';
//                $newacct->type = '3';
//                if($populate->DEBIT == "FALSE" || $populate->DEBIT == "0"){
//                    $newacct->cr_db_indic = '1';
//                    $newacct->credit=$populate->creditamount;
//                }else{
//                    $newacct->cr_db_indic = '0';
//                    $newacct->debit=$populate->debitamount;
//                }
//                $newacct->save();                
//            }
//            $populates = DB::Select("Select *,sum(credit) as total from accountings group by refno");
//            foreach($populates as $populate){
//                $expalains = DB::Select("Select * from journal_old where VOUCHER_NO = $populate->refno group by VOUCHER_NO");
//                $newremark = new \App\AccountingRemark;
//                $newremark->refno = $populate->refno;
//                $newremark->trandate = $populate->transactiondate;
//                foreach($expalains as $expalain){
//                    $newremark->remarks = $expalain->EXPLANATION;
//                    $newremark->posted_by = $expalain->PREPARED_BY;                    
//                }
//                $newremark->save();                
//            }
//                $month1 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno','161306')->where('schoolyear','2016')->where('month','JUN')->orderBy('id','DESC')->first();
//                $month1->age = 7;
//            //return count($populates);
//                echo print_r($month1);
//                return $month1;
                
                
            }
            
            function updatecdbdrcr(){
                $entry_type = "7";
                $updates = \App\Accounting::where('type','7')->get();
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
                         $add->postedby = 'system';
                         $add->save();
                         
                    }
                    
                    return count($updates);
            }
            function updatecdb2(){
                $disbursements = DB::Select("Select * from forwarded_cbd2 WHERE DEBIT IN('TRUE',0) AND `accounttitle` IN ('East West Bank CA 035-02-04076-5','BPICA 1881-0466-59','CBC-CA 1049-00 00027-8','BPI- CA 1885-1129-82') group by VOUCHER_NO");
                
                foreach($disbursements as $disbursement){
                    $newcdb = new \App\Disbursement;
                    $newcdb->transactiondate = $disbursement->TR_DATE;
                    $newcdb->bank = $disbursement->accounttitle;
                    $newcdb->refno = $disbursement->VOUCHER_NO;
                    $newcdb->payee  = $disbursement->PAYEE;
                    $newcdb->voucherno = $disbursement->VOUCHER_NO;
                    $newcdb->checkno = $disbursement->BANK_CHECK_NO;
                    $newcdb->amount = $disbursement->ACCOUNT_AMOUNT;
                    $newcdb->remarks = $disbursement->EXPLANATION;
                    $newcdb->postedby = $disbursement->PREPARED_BY;
                    $newcdb->save();
            
                    echo $newcdb->voucherno." - ".$newcdb->bank."<br>";
                }
        
                return 0;
            }
        
            function createCdbRecs(){
                $disbursements = DB::Select("Select * from forwarded_cbd2");
                
                foreach($disbursements as $disbursement){
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
                
            }
            
            function givePass(){
                $users = \App\User::where('accesslevel',30)->where('password','')->get();
                foreach($users as $user){
                    $account = \App\User::find($user->id);
                    $account->password = bcrypt($user->idno);
                    $account->save();
                }
            }
            
            function fixDepartment(){
                $fixes = DB::Select("Select * from ledgers where schoolyear = ' 2016' and acct_department=''");
//                
//                foreach($fixes as $fix){
//                    $acct_dept = "";
//                    $sub_dept = "";
//                    $ctr = \App\CtrPaymentSchedule::where('level',$fix->level)->where('accountingcode',$fix->accountingcode)->where('accountingcode',$fix->accountingcode)->first();
//                    
//                    if($ctr){
//                        $acct_dept = $ctr->acct_department;
//                        $sub_dept = $ctr->sub_department;
//                    }
//                    echo $acct_dept." - ".$fix->id." - ".$ctr."<br>";
//                    $account = \App\Ledger::find($fix->id);
//                    $account->acct_department = $acct_dept;
//                    $account->sub_department = $sub_dept;
//                    $account->save();
//                }
                return $fixes;
            }
            
            function fixDepartmentCredit(){
                $credits = DB::Select("Select * from credits where acct_department='' and entry_type = 1 and schoolyear = 2017");
                //$credits = \App\Credit::where('acct_department','')->where('entry_type',1)->get();
                foreach($credits as $credit){
                    $acct_dept = "";
                    $sub_dept = "";
                    $ledger = \App\Ledger::find($credit->referenceid);
                    
                    if($ledger){
                        $acct_dept = $ledger->acct_department;
                        $sub_dept = $ledger->sub_department;
                    }
                    echo $acct_dept." - ".$ledger->id." - ".$ledger."<br>";
                    $account = \App\Credit::find($credit->id);
                    $account->acct_department = $acct_dept;
                    $account->sub_department = $sub_dept;
                    $account->save();
                }
            }
            
            function getUnearned(){
                $accounts = \App\ChartOfAccount::get();
                
                foreach($accounts as $account){
                    
                    $credits = \App\Credit::where('accountingcode',$account->acctcode)->where('entry_type',2)->where('isreverse',0)->sum('amount');
                    $debits = \App\Dedit::where('accountingcode',$account->acctcode)->where('entry_type',2)->where('isreverse',0)->sum('amount');
                    
                    
                    echo $account->accountname.">".$account->acctcode.">".$debits.">".$credits."<br>";
                }
                
                return "Done";
            }
            
            function gradeMigration2(){
                $grades  = DB::select("Select * from old_grades where sy_effective = 2015 and level = 'Grade 6'");
                ini_set("memory_limit","850M"); 
                set_time_limit('1000'); 
                
                foreach($grades as $grade ){
                    $student = \App\User::where('idno',$grade->scode)->exists();
                    if($student){
                        $exist = \App\Grade::where('idno',$grade->scode)->where('schoolyear',$grade->sy_effective)->where('subjectcode',$grade->subj_code)->exists();
                        if($exist){
                            $import = \App\Grade::where('idno',$grade->scode)->where('schoolyear',$grade->sy_effective)->where('subjectcode',$grade->subj_code)->first();
                        }else{
                            $import = new \App\Grade;
                            $import->idno = $grade->scode;
                            $import->level = $grade->level;
                            $import->subjectcode = $grade->subj_code;
                            if(in_array($grade->subj_code,array('DAYA','DAYP','DAYT'))){
                                $import->subjecttype = 2;
                            }else{
                                $import->subjecttype = 0;
                            }

                            $import->schoolyear = $grade->sy_effective;
                        }

                        $import->first_grading = $grade->first;
                        $import->second_grading = $grade->second;
                        $import->third_grading = $grade->third;
                        $import->fourth_grading = $grade->fourth;
                        $import->save();                        
                    }

                    
                }
            }
}
