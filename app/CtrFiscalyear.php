<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtrFiscalyear extends Model
{
    public static function prevFiscal(){
        $fiscal = CtrFiscalyear::first();
        
        if($fiscal){
            return $fiscal->fiscalyear - 1 ;
        }else{
            return 0;
        }
    }
    
    public static function fiscalyear(){
        $fiscal = CtrFiscalyear::first();
        
        if($fiscal){
            return $fiscal->fiscalyear ;
        }else{
            return 0;
        }
    }
    
    public static function date_fiscalyear($date){
        $accountingdate = date_create(date("Y-M-d",strtotime($date)));
        
        if(((int)$accountingdate->format("m")) <= 4){
            $fiscalyear = $accountingdate->format("Y")-1;
            
        }else{
            $fiscalyear = $accountingdate->format("Y");
        }
        
        return $fiscalyear;
    }
    
    public static function fiscalstart($fiscalyear){
        $fiscalstart = $fiscalyear."-05-01";
        
        return $fiscalstart;
    }
    
    public static function fiscalend($fiscalyear){
        $fiscalstart = ($fiscalyear+1)."-04-30";
        
        return $fiscalstart;
    }
            
}
