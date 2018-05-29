<?php

namespace App\Http\Controllers\Accounting\Budgeting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Accounting\Budgeting\BudgetHelper;
use Khill\Lavacharts\Lavacharts;

class BudgetReport extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function index($from,$to){
        //Check if fiscal year matches If not redirect from the nearest date accordung to $from
        $from_fy = \App\CtrFiscalyear::date_fiscalyear($from);
        $to_fy = \App\CtrFiscalyear::date_fiscalyear($to);
        
        if($from_fy != $to_fy){
            $new_to = \App\CtrFiscalyear::fiscalend($from_fy);
            return redirect()->route('budgetreport',[$from,$new_to]);
        }
        
        //If it matched continue to render
        $fiscalyear = $from_fy;
        
        return view('accounting.Budgeting.reports.index',compact('from','to','fiscalyear'));
    }
    
    static function renderMainReport($from,$to,$fiscalyear,$type){
        $expenses = BudgetHelper::expenses_ranged($from, $to, $fiscalyear);
        $departments = \App\CtrAcctDep::all()->sortBy('main_department');
        
        return view('accounting.Budgeting.reports.MainReports.accumulative',compact('from','to','fiscalyear','departments','expenses'))->render();
    }
    
    static function pieGraph($from,$to,$fiscalyear){
        $lava = new Lavacharts;
        
        $reasons->addStringColumn('Reasons')
                ->addNumberColumn('Percent')
                ->addRow(['Check Reviews', 5])
                ->addRow(['Watch Trailers', 2])
                ->addRow(['See Actors Other Work', 4])
                ->addRow(['Settle Argument', 89]);

        $lava->PieChart('IMDB', $reasons, [
            'title'  => 'Reasons I visit IMDB',
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ]);
        return $chartjs;
    }
    
    static function accumulatedReport($from,$to,$fiscalyear){
        
    }
    
    static function monthlyReport($from,$to,$fiscalyear){
        
    }
}
