<?php
//getparticular
namespace App\Http\Controllers\Cashier;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Request;
use DB;

class AjaxController extends Controller{
    function getaccount($group){
        if(Request::ajax()){
            $accounts = DB::Select("select distinct accountname,acctcode from chart_of_accounts where acctcode like '$group%' order by accountname");
            $data = "";
            if(count($accounts)>0){
                $data = $data."<option value='' hidden='hidden'>-- Select Account Group--</option>";
            }
            foreach($accounts as $account){
                $data = $data. "<option value='".$account->acctcode."'>".$account->accountname."</option>";
            }
        return $data;    
        }

    }
    
    function getaccount2($group){
        if(Request::ajax()){
            $accounts = DB::Select("select distinct accountname,acctcode from chart_of_accounts where acctcode like '$group%' order by accountname");
            $data = "";
            if(count($accounts)>0){
                $data = $data."<option value='' hidden='hidden'>-- Select Account Group--</option>";
            }
            foreach($accounts as $account){
                $data = $data. "<option value='".$account->accountname."'>".$account->accountname."</option>";
            }
        return $data;    
        }

    }
    
    function getparticular($group){
        if(Request::ajax()){
            $particulars = DB::Select("select particular from ctr_other_payments where acctcode like '$group'");
            $data = "";
            if(count($particulars)>0){
                $data = $data."<option value=' ' hidden='hidden'>-- Select Particular--</option>";
            }
            foreach($particulars as $particular){
                $data = $data. "<option value='".$particular->particular."'>".$particular->particular."</option>";
            }
        return $data;    
        }

    }
}
