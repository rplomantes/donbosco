<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Accounting\Budgeting\BudgetHelper;

class DepartmentBudget extends Model
{
    
    //Department Properties
    public static function department_AnnualBudget($departmentname,$fiscalyear){
        $budget = 0;
        $department = DepartmentBudget::where('department',$departmentname)->where('fiscalyear',$fiscalyear)->get();
        
        if(count($department) > 0){
            $budget = $department->sum("budget_amount");
        }
        if($budget == 0){
            return "N/A";
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
            return "N/A";
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
            return "N/A";
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
            return "N/A";
        }
        
        
        return round($utilization,2)."%";
    }
    
    //END Office Properties
    
}
