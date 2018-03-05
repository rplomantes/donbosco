<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    static function getaccttotal($credit,$debit,$entry){
        $total = 0;
        
        
        if($entry == 'debit'){
            $total = $debit - $credit;
        }else{
            $total = $credit - $debit;
        }
        
        return $total;
    }
    
    static function allaccttotal($accounts){
        $creditaccts = 0;
        $debitaccts = 0;
        foreach($accounts as $account){
            $accountingcode = substr($account->accountingcode,0,1);

                if($accountingcode == 1 || $accountingcode == 3 || $accountingcode == 5){
                    $amount = self::getaccttotal($account->credits,$account->debit,$account->accountingcode);
                    $debitaccts = $debitaccts + $amount;
                }else{
                    $amount = self::getaccttotal($account->credits,$account->debit,$account->accountingcode);
                    $creditaccts = $creditaccts + $amount;
                }
        }
            
            return $creditaccts - $debitaccts;
    }
    
    static function getfiscalyear($date){
        $date = date_create(date("Y-M-d",strtotime($date)));
        
        if(((int)$date->format("m")) <= 4){
            $fiscalyear = $date->format("Y")-1;
            
        }else{
            $fiscalyear = $date->format("Y");
        }
        
        return $fiscalyear;
    }
    
    static function fiscalBeginning($fiscalyear){
        return $fiscalyear."-05-01";
    }
    
    static function get_Department($office){
        $department = "None";
        
        $departments = \App\CtrAcctDep::where('sub_department',$office)->first();
        
        if($departments){
            $department = $departments->main_department;
        }
        
        return $department;
    }
    
    static function get_nonStudent($idno){
        $name = "";
        $student = \App\NonStudent::where('idno',$idno)->first();
        if($student){
            $name = $student->fullname;
        }
        
        return $name;
    }
    
    static function get_entryType($type){
        switch($type){
            case 1:
                return "Cash Receipt";
            case 2:
                return "Debit Memo";
            case 3:
                return "General Journal";
            case 4:
                return "Disbursement";
            default :
                return "System Generated";
        }
    }
}
