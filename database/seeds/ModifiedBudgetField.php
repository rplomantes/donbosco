<?php

use Illuminate\Database\Seeder;

class ModifiedBudgetField extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Subsidy
        $subsidies = ['Less: 10% ROI','Contingency Fund','Subsidy to Student Services Department','Subsidy from TVET','Subsidy to Pastoral Department','Subsidy to Administration Department'];
        $sort = 1;
        foreach($subsidies as $group=>$subsidy){
            if(is_array($subsidy)){
                foreach($subsidy as $sub){
                    $this->saveMe('subsidy','subsidy'.$sort,$group,$sub,$sort);
                    $sort++;
                }
            }else{
                $this->saveMe('subsidy','subsidy'.$sort,$subsidy,$subsidy,$sort);
                $sort++;
            }
            
        }
        
        //Revenue
        $revenues = ['Tuition Fees','Registration & Other Fees','Department Facilities','Auxillary Services ','Other Educational Income','Other Income'];
        $sort = 1;
        foreach($revenues as $group=>$revenue){
            if(is_array($revenue)){
                foreach($revenue as $sub){
                    $this->saveMe('revenue','revenue'.$sort,$group,$sub,$sort);
                    $sort++;
                }
            }else{
                $this->saveMe('revenue','revenue'.$sort,$revenue,$revenue,$sort);
                $sort++;
            }
            
        }
        
        
        //Personnel Expense
        $personel_expenses = ['Salaries & Allowances',
            'Bonus & 13th month pay',
            'SSS, Philhealth & HDMF contributions',
            'Staff Personnel Development',
            'Retreat/Recollection',
            'Contributed Services',
            'Other employees benefits',
            'Sports & Social',
            'Professional Fees'];
        
        $sort = 1;
        foreach($personel_expenses as $group=>$personel_expense){
            if(is_array($personel_expense)){
                foreach($personel_expense as $sub){
                    $this->saveMe('personel_expense','personel_expense'.$sort,$group,$sub,$sort);
                    $sort++;
                }
            }else{
                $this->saveMe('personel_expense','personel_expense'.$sort,$personel_expense,$personel_expense,$sort);
                $sort++;
            }
            
        }
        
        //Other Expenses 
        $other_expenses = ['Depreciation & Amortization','Electricity','Water','Repairs & Maintenance',
            'Communication','Agency Services','School & Office Supplies','Instructional Materials',
            'Library & Audio Visual Supplies','Laboratory Supplies','PE & Athletic Supplies',
            'Medical/Dental Supplies','Transportation & Travel(travel, gas & oil)',
            'Maintenance supplies','Books, Publication & Subscription',
            'Youth Asistance','Legal & Audit Fees','Taxes & Licenses','Provincial Contributions',
            'Donation','Insurance','School Activities','Representation Expense',
            'Advertising & Promotions','Miscellaneous'];
        
        $sort = 1;
        foreach($other_expenses as $group=>$other_expense){
            if(is_array($other_expense)){
                foreach($other_expense as $sub){
                    $this->saveMe('other_expense','other_expense'.$sort,$group,$sub,$sort);
                    $sort++;
                }
            }else{
                $this->saveMe('other_expense','other_expense'.$sort,$other_expense,$other_expense,$sort);
                $sort++;
            }
            
        }
    }
    
    function saveMe($type,$entry_code,$group,$sub_group,$sort){
            $budget = new \App\BudgetField();
            $budget->type = $type;
            $budget->entry_code = $entry_code;
            $budget->group = $group;
            $budget->sub_group = $sub_group;
            $budget->sort = $sort;
            $budget->save();
    }
}
