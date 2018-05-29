<?php

use Illuminate\Database\Seeder;

class BudgetAccounts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Revenue
        $revenues =['revenue1'=>[410000],
                    'revenue2'=>[420000],
                    'revenue3'=>[420100],
                    'revenue4'=>[420300,420400,430000,430100,430200,440000],
                    'revenue5'=>[420200,440100,440400],
                    'revenue6'=>[440200,440600,440700]];
        
        $personnel_expenses =['personel_expense1'=>[500000],
                            'personel_expense2'=>[500100],
                            'personel_expense3'=>[500600,500700,500800,500900],
                            'personel_expense4'=>[500500],
                            'personel_expense5'=>[501300],
                            'personel_expense6'=>[530000],
                            'personel_expense7'=>[500300],
                            'personel_expense8'=>[501200],
                            'personel_expense9'=>[510000]];
        
        $other_expenses =[  'other_expense1'=>[561500],
                            'other_expense2'=>[570000],
                            'other_expense3'=>[570100],
                            'other_expense4'=>[540000,540100,540200,540300,540400,540500,540600,540700,540800,540900,541000,541100,541200,541300,541400],
                            'other_expense5'=>[570200],
                            'other_expense6'=>[550000],
                            'other_expense7'=>[580400],
                            'other_expense8'=>[580000],
                            'other_expense9'=>[580100],
                            'other_expense10'=>[580200],
                            'other_expense11'=>[580500],
                            'other_expense12'=>[580300],
                            'other_expense13'=>[590200,590300],
                            'other_expense14'=>[551000],
                            'other_expense15'=>[590000],
                            'other_expense16'=>[530200],
                            'other_expense17'=>[510100],
                            'other_expense18'=>[590500],
                            'other_expense19'=>[530100],
                            'other_expense20'=>[520400],
                            'other_expense21'=>[590100],
                            'other_expense22'=>[520200],
                            'other_expense23'=>[520300],
                            'other_expense24'=>[590400]];
        
        
        
        $this->loopTrough($revenues);
        $this->loopTrough($personnel_expenses);
        $this->loopTrough($other_expenses);
    }
    
    function loopTrough($groups){        
        foreach($groups as $field=>$acctcodes){
            
            foreach($acctcodes as $acctcode=>$subsidiaries){
                
                
                if(is_array($subsidiaries)){
                    $this->command->info($acctcode);
                    foreach($subsidiaries as $subs){
                        $this->command->info($subs);
                        $this->addAccount($field, $acctcode, $subs);
                    }
                }else{
                    $this->command->info($subsidiaries);
                    $this->addAccount($field, $subsidiaries);
                }
                
                
            }
        }
    }
    
    function addAccount($fieldcode,$accountcode,$subsidiary=''){
        $chartAccount = \App\ChartOfAccount::where('acctcode',$accountcode)->first();
                
                
        $addAccount = new App\BudgetFieldAccounts();
        $addAccount->field = $fieldcode;
        $addAccount->accountingcode = $accountcode;
        $addAccount->accountname = $chartAccount->accountname;
        if($subsidiary != ''){
            $addAccount->subsidiary = $subsidiary;
        }
        $addAccount->save();
    }
}
