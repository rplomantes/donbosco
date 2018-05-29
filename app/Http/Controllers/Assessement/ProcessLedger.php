<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StudentInfo as Info;
use App\Http\Controllers\Assessement\PlanController as Plan;
use App\Http\Controllers\Helper as MainHelper;
use Carbon\Carbon;

class ProcessLedger extends Controller
{
    static function processLedger($idno,$level,$strand,$plan){
        $sy = MainHelper::get_enrollmentSY();
        self::ledgerToPrev($idno,$sy);
        self::deleteLedger($idno,$sy);
        self::createLedger($idno, $level, $strand, $plan, $sy);
    }
    
    static function ledgerToPrev($idno,$sy){
        $accounts = \App\Ledger::where('idno',$idno)->where('schoolyear','!=',$sy)->where('categoryswitch','<=',7)->get();
        
        foreach($accounts->groupBy('schoolyear') as $accountgroups){
            $status = Info::get_status($idno, $accountgroups->pluck('schoolyear')->last());
            if(in_array($status,array(0,1))){
                foreach($accountgroups as $account){
                    self::moveToDeleted($account);
                }   
            }else{
                
                foreach($accountgroups as $account){
                    
                    $account->update(['categoryswitch'=>$account->categoryswitch+10]);
                }
            }
        }
    }
    
    static function deleteLedger($idno,$schoolyear){
        \App\Ledger::where('idno',$idno)->where('schoolyear',$schoolyear)->delete();
    }
    
    static function moveToDeleted($account){
        $deletedRecord = new \App\DeletedAccount();
        $deletedRecord->idno = $account->idno;
        $deletedRecord->transactiondate = $account->transactiondate;
        $deletedRecord->department = $account->department;
        $deletedRecord->level = $account->level;
        $deletedRecord->course = $account->course;
        $deletedRecord->strand= $account->strand;
        $deletedRecord->categoryswitch = $account->categoryswitch;
        $deletedRecord->accountingcode = $account->accountingcode;
        $deletedRecord->acctcode = $account->acctcode;
        $deletedRecord->description =$account->description;
        $deletedRecord->receipt_details = $account->receipt_details;
        $deletedRecord->amount = $account->amount;
        $deletedRecord->schoolyear = $account->schoolyear;
        $deletedRecord->duetype = $account->duetype;
        $deletedRecord->period = $account->period;
        $deletedRecord->duedate = $account->duedate;
        $deletedRecord->postedby = $account->postedby;
        $deletedRecord->deleted = "Student did not enroll";
        $deletedRecord->save();
    }
    
    static function createLedger($idno, $level, $strand, $plan, $sy){
        $newAccounts = Plan::get_plan($plan, $level, $strand);
        
        foreach($newAccounts as $account){
            $newRecord = new \App\Ledger();
            $newRecord->idno = $idno;
            $newRecord->transactiondate = Carbon::now();
            $newRecord->department = $account->department;
            $newRecord->level = $level;
            $newRecord->course = $account->course;
            $newRecord->strand= $strand;
            $newRecord->categoryswitch = $account->categoryswitch;
            $newRecord->accountingcode = $account->accountingcode;
            $newRecord->acctcode = $account->acctcode;
            $newRecord->description =$account->description;
            $newRecord->receipt_details = $account->receipt_details;
            $newRecord->amount = $account->amount;
            $newRecord->plandiscount = $account->discount;
            $newRecord->acct_department = $account->acct_department;
            $newRecord->sub_department = $account->sub_department;
            $newRecord->schoolyear = $sy;
            $newRecord->duetype = $account->duetype;
            $newRecord->period = $account->period;
            $newRecord->duedate = $account->duedate;
            $newRecord->postedby = $idno;
            $newRecord->save();
        }
    }
}
