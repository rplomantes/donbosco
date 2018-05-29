<?php

namespace App\Http\Controllers\Registrar\Discount;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DiscountGrant extends DiscountHelper
{
    static function view_discountGrant($idno){
        $schooyear = \App\CtrYear::getYear('schoolyear');
        $grant = \App\DiscountGrant::where('schoolyear',$schooyear)->where('idno',$idno)->first();
        
        if($grant){
            return view('registrar.assessment.subview.discountGrant',compact('grant'))->render();
        }
        
        
        return null;
    }
    
    static function set_applyDiscount($idno){
        $schooyear = \App\CtrYear::getYear('schoolyear');
        $grant = \App\DiscountGrant::where('schoolyear',$schooyear)->where('idno',$idno)->first();
        $ledger= \App\Ledger::where('schoolyear',$schooyear)->where('idno',$idno)->get();
        
        $accountInfos = [
            ["field"=>'tuitionfee','accountingcode'=>120100],
            ["field"=>'miscfee','accountingcode'=>420400],
            ["field"=>'elearningfee','accountingcode'=>420200],
            ["field"=>'departmentfee','accountingcode'=>420100],
            ["field"=>'bookfee','accountingcode'=>440400]
                ];
        foreach($accountInfos as $accountInfo){
            if($grant->$accountInfo['field'] > 0){
                $discountcode = self::get_discountGrantCode($accountInfo['accountingcode'], $grant->discount_type);
                $accounts = $ledger->where('accountingcode',$accountInfo['accountingcode'],false);
                self::breakdownDiscount($accounts, $grant->$accountInfo['field'],$discountcode);
                self::normalizeDiscount($accounts);
            }   
        }        
    }
    
    
    
    static function breakdownDiscount($accounts,$discountAmount,$discountCode){
        if($accounts->count() > 0){
            //while ($discountAmount > 0){
                foreach($accounts as $account){
                    $idealDiscount = $account->amount - $account->otherdiscount;
                    if($idealDiscount > $discountAmount){
                        $addDisc = $discountAmount;
                    }else{
                        $addDisc = $idealDiscount;
                    }
                    $account->otherdiscount = $account->otherdiscount+$addDisc;
                    $account->discountcode = $discountCode;
                    $account->save();
                    
                    $discountAmount = $discountAmount - $addDisc;
                }
            //}
        }
    }
    
    static function normalizeDiscount($accounts){
        foreach($accounts as $account){
            $findAccount = \App\Ledger::where('id',$account->id)->first();
            if($findAccount){
                $totalDiscount = $findAccount->plandiscount + $findAccount->otherdiscount;
                $discountoverage = 0;

                if($totalDiscount > $findAccount->amount){
                    $discountoverage = $totalDiscount - $findAccount->amount;
                }
                
                if($findAccount->plandiscount > 0){
                    $findAccount->plandiscount = $findAccount->plandiscount - $discountoverage;
                    $findAccount->save();   
                }
            }            
        }
    }
}
