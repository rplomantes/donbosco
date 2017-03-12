<?php
//getparticular
namespace App\Http\Controllers\Cashier;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Request;


class AjaxController extends Controller
{
    function getaccount($group){
        if(Request::ajax()){
            $accounts = \App\CtrOtherPayment::where("acctcode",'LIKE',"$group%")->get();
            $data = "";
            if(count($accounts)>0){
                $data = $data."<option value='' hidden='hidden'>-- Select Account Group--</option>";
            }
            foreach($accounts as $account){
                $data = $data. "<option value='".$account->accounttype."'>".$account->accounttype."</option>";
            }
        return $data;    
        }

    }
}
