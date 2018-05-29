<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetAccounts extends Model
{
    
    //Institutional Properties
    public static function institutional_annualBudget($fiscalyear,$entry_code=null,$office=null){
        $budget = 0;
        
        $account = BudgetAccounts::where('fiscalyear',$fiscalyear)->first();
        
        if($account){
            
            if($entry_code != null){
                $account = $account->where('entry_code',$entry_code,false);
            }
            
            if($office != null){
                $account = $account->where('sub_department',$office,false);
            }
            
            $budget = $budget + $account->sum("amount");
        }
        
        if($budget == 0){
            return ['amount'=>$budget,'display'=>'-'];
        }
        
        return ['amount'=>$budget,'display'=>number_format($budget,2,'.',',')];
        
    }
    
    public static function institutional_RemainingBudget($fiscalyear,$totalexpense,$accountcode=0){
        $budget = self::institutional_annualBudget($fiscalyear,$accountcode);
        
        if($budget['amount'] == 0){
            return ['amount'=>round(0,2),'display'=>'-'];
        }
        
        $remaining_budget = round($budget['amount'],2) - round($totalexpense,2);
        
        return ['amount'=>round($remaining_budget,2),'display'=>number_format($remaining_budget,2,'.',',')];
    }
    
    
    public static function institutional_annualUtilization($fiscalyear,$totalexpense,$accountcode=0){
        $budget = self::institutional_annualBudget($fiscalyear,$accountcode);
        
        if($budget['amount'] > 0){
            $utilization  = (round($totalexpense,2) / round($budget['amount'],2)) * 100;
        }else{
            return "-";
        }
        
        
        return round($utilization,2)."%";
    }
    
    //Department Properties
    public static function department_AnnualBudget($departmentname,$fiscalyear){
        $budget = 0;
        $department = DepartmentBudget::where('department',$departmentname)->where('fiscalyear',$fiscalyear)->get();
        
        if(count($department) > 0){
            $budget = $department->sum("budget_amount");
        }
        if($budget == 0){
            return "-";
        }
        
        return $budget;
    }
    
    public static function department_RemainingBudget($departmentname,$fiscalyear,$totalexpense){
        $budget = self::department_AnnualBudget($departmentname, $fiscalyear);
        
        $remaining_budget = round($budget,2) - round($totalexpense,2);
        
        return round($remaining_budget,2);
    }
    
    public static function department_Utilization($departmentname,$fiscalyear,$totalexpense){
        $budget = self::department_AnnualBudget($departmentname, $fiscalyear);
        
        if($budget > 0){
            $utilization  = (round($totalexpense,2) / round($budget,2)) * 100;
        }else{
            return "-";
        }
        
        
        return round($utilization,2)."%";
    }
    
    //END Department Properties
    
    //Office Properties
    
    public static function office_AnnualBudget($officename,$fiscalyear){
        $budget = 0;
        $department = DepartmentBudget::where('sub_department',$officename)->where('fiscalyear',$fiscalyear)->first();
        
        if($department){
            $budget = $department->budget_amount;
        }
        
        if($budget == 0){
            return "-";
        }
        
        return $budget;
    }
    
    
    // Office Remaining Budget
    public static function office_RemainingBudget($officename,$fiscalyear,$totalexpense){
        $budget = self::office_AnnualBudget($officename, $fiscalyear);
        
        $remaining_budget = round($budget,2) - round($totalexpense,2);
        
        return round($remaining_budget,2);
        
    }
    
    public static function office_Utilization($officename,$fiscalyear,$totalexpense){
        $budget = self::office_AnnualBudget($officename, $fiscalyear);
        
        if($budget > 0){
            $utilization  = (round($totalexpense,2) / round($budget,2)) * 100;
        }else{
            return "-";
        }
        
        
        return round($utilization,2)."%";
    }
    
    //END Office Properties
    
}
