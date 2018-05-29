<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    public function CtrLevel(){
        return $this->hasOne('\App\CtrLevel','level','level');
    }
    
    public static function TotalDue($idno){
        $dues = Ledger::where('idno',$idno)->get()->filter(function($account){
            return $account->duedate <= date('Y-m-d',strtotime(\Carbon\Carbon::now())) && $account->categoryswitch <= 7;
            
        });
        
        //Variable
        $totaldue = 0;
        $totalpaid = 0;
        
        foreach($dues as $due){
            $totaldue = $totaldue + $due->amount;
            $totalpaid = $totalpaid + ($due->payment + $due->plandiscount + $due->debitmemo + $due->otherdiscount);
        }
        return round($totaldue,2) - round($totalpaid,2);
    }
}
