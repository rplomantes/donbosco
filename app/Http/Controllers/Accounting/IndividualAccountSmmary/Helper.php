<?php

namespace App\Http\Controllers\Accounting\IndividualAccountSmmary;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Accounting\Disbursement\Helper as DisbHelper;
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

class Helper extends Controller
{
    static function accounts($from,$to,$account,$grouping){
        if($grouping == ""){
            $groupBy = array('id');
        }elseif($grouping == "byDepartment"){
            $groupBy = array('refno','acct_department','sub_department');
        }else{
            $groupBy = array('refno','acct_department','sub_department','description');
        }
            
        $debits = self::debits($from, $to, $account,$groupBy);
        $credits = self::credits($from, $to, $account,$groupBy);
        
        return $debits->merge($credits);
        
    }
    
    static function debits($from,$to,$account,$groupBy){
        $accounts = \App\Dedit::selectRaw('*,amount as totalamount,sum(checkamount) as totalcheck')->whereBetween('transactiondate',[$from,$to])->where('accountingcode',$account)->where('isreverse',0)->groupBy($groupBy)->get();
        $debits = array();
        
        foreach($accounts as $acct){
            $level = Info::get_level($acct->idno, $acct->schoolyear);
            $section = Info::get_section($acct->idno, $acct->schoolyear);
            if($acct->entry_type == 4){
                $payee = DisbHelper::get_payee($acct->refno);
                $remarks = DisbHelper::get_remarks($acct->refno);
            }else{
                $payee = $acct->receivefrom;
                $remarks = $acct->remarks;
            }
            
            $debits[] = (object)['schoolyear'=>$acct->schoolyear,'refno'=>$acct->refno,'idno'=>$acct->idno,'entry_type'=>$acct->entry_type,'created_at'=>$acct->created_at,'transactiondate'=>$acct->transactiondate,'receiptno'=>$acct->receiptno,
                'payee'=>$payee,'debit'=>$acct->totalamount+$acct->totalcheck,'credit'=>0,'acctdepartment'=>$acct->acct_department,'subdepartment'=>$acct->sub_department,'particular'=>$remarks,'subaccount'=>$acct->description,'level'=>$level,'section'=>$section];
        }
        
        return collect((object)$debits);
    }
    
    static function credits($from,$to,$account,$groupBy){
        $accounts =  \App\Credit::selectRaw('*,amount as totalamount')->with('Dedit')->whereBetween('transactiondate',[$from,$to])->where('accountingcode',$account)->where('isreverse',0)->groupBy($groupBy)->get();
        $credits = array();
        
        foreach($accounts as $acct){

            
            $level = Info::get_level($acct->idno, $acct->schoolyear);
            $section = Info::get_section($acct->idno, $acct->schoolyear);
            
            if($acct->entry_type == 4){
                $payee = DisbHelper::get_payee($acct->refno);
                $remarks = DisbHelper::get_remarks($acct->refno);
            }else{
                $payee = $acct->dedit->pluck('receivefrom')->last();
                $remarks = $acct->dedit->pluck('remarks')->last();
            }
            
            $credits[] = (object)['schoolyear'=>$acct->schoolyear,'refno'=>$acct->refno,'idno'=>$acct->idno,'entry_type'=>$acct->entry_type,'created_at'=>$acct->created_at,'transactiondate'=>$acct->transactiondate,'receiptno'=>$acct->receiptno,
                'payee'=>$payee,'debit'=>0,'credit'=>$acct->totalamount,'acctdepartment'=>$acct->acct_department,'subdepartment'=>$acct->sub_department,'particular'=>$remarks,'subaccount'=>$acct->description,'level'=>$level,'section'=>$section];
        }
        
        return collect((object)$credits);
    }
}
