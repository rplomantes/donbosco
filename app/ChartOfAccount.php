<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{

    
    public static function getAccountName($accountingcode){
        $accountname = "";
        $account = ChartOfAccount::where('acctcode',$accountingcode)->first();
        if($account){
            $accountname = $account->accountname;
        }
        
        return $accountname;
    }
}
