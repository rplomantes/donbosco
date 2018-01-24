<?php

namespace App\Http\Controllers\Economer;

use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class DisbursementSummary extends Controller
{
    function index(){
        $fiscalyear = \App\CtrFiscalyear::first()->fiscalyear;
        $disbursements  = DB::Select("SELECT sum(amount) as amount,EXTRACT(MONTH from transactiondate) as month "
                . "FROM disbursements where transactiondate >= '$fiscalyear-05-01' "
                . "group by EXTRACT(MONTH from transactiondate)");
        
        $this->piechart($disbursements);
        return view('economic.disbursementReport',compact('disbursements'));
    }
    
    function piechart($disbursements){
            $reasons = \Lava::DataTable();

            $reasons->addStringColumn('Reasons')
                    ->addNumberColumn('Percent')
                    ->addRow(['Check Reviews', 5])
                    ->addRow(['Watch Trailers', 2])
                    ->addRow(['See Actors Other Work', 4])
                    ->addRow(['Settle Argument', 89]);

            \Lava::PieChart('Disbursements', $reasons, [
                'title'  => 'Reasons I visit IMDB',
                'is3D'   => true,
                'slices' => [
                    ['offset' => 0.2],
                    ['offset' => 0.25],
                    ['offset' => 0.3]
                ]
            ]);
    }
}
