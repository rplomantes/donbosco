<?php

use Illuminate\Database\Seeder;

class BudgetCtrAccounts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('budget_fields')->insert([
            [
                'type'=>'subsidy',
                'fieldname'=>'subsidy',
                'name'=>'Less: 10% ROI',
                'sort'=>1,
                    ],
            [
                'type'=>'subsidy',
                'accountingcode'=>'subsidy2',
                'accountgroup'=>'subsidy',
                'name'=>'Contingency Fund',
                'sort'=>2,
                    ],
            [
                'type'=>'subsidy',
                'accountingcode'=>'subsidy3',
                'accountgroup'=>'subsidy',
                'name'=>'Subsidy to Student Services Department',
                'sort'=>3,
                    ],
            [
                'type'=>'subsidy',
                'accountingcode'=>'subsidy4',
                'accountgroup'=>'subsidy',
                'name'=>'Subsidy from TVET',
                'sort'=>4,
                    ],
            [
                'type'=>'subsidy',
                'accountingcode'=>'subsidy5',
                'accountgroup'=>'subsidy',
                'name'=>'Subsidy to Pastoral Department',
                'sort'=>5,
                    ],
            [
                'type'=>'subsidy',
                'accountingcode'=>'subsidy6',
                'accountgroup'=>'subsidy',
                'name'=>'Subsidy to Administration Department',
                'sort'=>6,
                    ],
        ]);
        

        $accounts = \App\ChartOfAccount::get();
        //First Revenue Accounts Ungrouped like Tuition Fees,Registration & Other Fees,Department Facilities
        $independent_accts = $accounts->filter(function($query){
            return in_array($query->acctcode, [410000,420000,420100]);
        });
        $sort = 1;
        foreach ($independent_accts as $independent_acct){
            $this->writeMeALetter('revenue', $independent_acct->accountname, $independent_acct, $sort);
            $sort++;
        }
        
        //Second Revenue Accounts Grouped Accounts
        $groups = ["Auxillary Services"=>[420300,420400,430000,430100,430200,440000],
            "Other Educational Income"=>[420200,440100,440400],
            "Other Income"=>[440200,440600,440700]
            ];
        
        $sort = 1;
        foreach($groups as $groupname=>$group){
            $group_accts = $accounts->filter(function($query) use($group){
                return in_array($query->acctcode, $group);
            });
            
            foreach($group_accts as $group_acct){
                $this->writeMeALetter('revenue',$groupname, $group_acct, $sort);
                $sort++;
            }
        }
        
        
        //Expenses Baby
        $accountGroups = \App\CtrAccountsGroup::with('accountgroup')->where('type',5)->get();
        $sort = 1;
        foreach($accountGroups as $accountGroup){
            foreach($accountGroup->accountgroup as $expense){
                $this->writeMeALetter('expense', $accountGroup->groupname, $expense->chartofaccount, $sort);
                $sort++;
            }
            
        }
        
        
        
        
        
        
        
    }
    
    function writeMeALetter($type,$group,$accts,$sort){
        $account = new \App\BudgetField();
        $account->type = $type;
        $account->accountgroup = $group;
        $account->accountingcode = $accts->acctcode;
        $account->name = $accts->accountname;
        $account->sort = $sort;
        $account->save();
    }
}
