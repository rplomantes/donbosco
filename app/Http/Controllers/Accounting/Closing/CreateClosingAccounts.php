<?php

namespace App\Http\Controllers\Accounting\Closing;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Accounting\Closing\ClosingHelper;

class CreateClosingAccounts extends Controller
{
    public static function reverseEntries($fiscalyear,$refno){
        $exceptions = [110000,110011,110012,110013,110014,110015,110016,110017,110018,110019,110020,110021,110022,110030,110040,120100,120101,120102,120103,120104,120105,120106,120107,120108,120109,120201,120202,120203,120204,120300,120400,130000,130100,130200,130001,130002,130003,130004,130005,130006,140100,140101,140102,140103,140104,140105,140106,140107,140108,140109,140110,140111,140112,140113,140114,140115,140116,140200,140201,140202,140203,140204,140205,140206,140207,140208,140209,140210,140211,140212,150300,150400,210000,210100,210101,210102,210103,210104,210105,210111,210112,210113,210114,210115,210200,210201,210300,210400,210500,220000];
        $entry_type = 8;
        
        $accounts = \App\ChartOfAccount::whereNotIn('acctcode',$exceptions)->get();
        foreach($accounts as $account){
            
            //Chacha get total
            $total = ClosingHelper::computeTotal($fiscalyear, $account->acctcode, $account->entry);
            
            //For Debit Accounts
            if($account->entry == 'debit'){
                if($total > 0){
                    ClosingHelper::create_credit($refno, $fiscalyear,$account->acctcode, $entry_type, $total);
                }else{
                    ClosingHelper::create_debit($refno, $fiscalyear,$account->acctcode, $entry_type, abs($total));
                }
            //For Credit Accounts
            }else{
                if($total > 0){
                    ClosingHelper::create_debit($refno, $fiscalyear,$account->acctcode, $entry_type, $total);
                }else{
                    ClosingHelper::create_credit($refno, $fiscalyear,$account->acctcode, $entry_type, abs($total));
                }
            }
        }
    }
}
