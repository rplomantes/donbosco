<?php

namespace App\Http\Controllers\Accounting\Disbursement;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;

class Book extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function disbursementbook($from,$trandate){

        \App\RptDisbursementBook::where('idno', \Auth::user()->idno)->delete();
        \App\RptDisbursementBookSundries::where('idno', \Auth::user()->idno)->delete();
        $disbursements = \App\Disbursement::whereBetween('transactiondate', array($from,$trandate))->get()->unique();
        $this->processdisbursementbook($disbursements,"0");

        $sundries = \App\Accounting::where('type',4)->where('isfinal',1)->where('isreversed',0)->whereBetween('transactiondate',array($from,$trandate))->get();
        $entries = \App\RptDisbursementBook::where('idno',\Auth::user()->idno)->get();



       return view('accounting.Disbursement.Book.book',compact('from','trandate','sundries','entries'));
    }
       
    function printdisbursementpdf($from,$to){
       ini_set('memory_limit', '-1');
        $sundries = \App\Accounting::where('type',4)->where('isfinal',1)->where('isreversed',0)->whereBetween('transactiondate',array($from,$to))->get();
        $entries = \App\RptDisbursementBook::where('idno',\Auth::user()->idno)->get();
        $trandate = $to;
        $group_count = 21;
        $title = 'CASH DISBURSEMENT BOOK';



        $pdf= \App::make('dompdf.wrapper');
        //$pdf->setPaper([0,0,612.00,936.00],'landscape');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView('accounting.Disbursement.Book.printbook',compact('title','from','to','entries','sundries','group_count'));
        return $pdf->stream();

    }
       
    function printdisbursementsundriespdf($from,$to){
        ini_set('memory_limit', '-1');


        $trandate = $to;
        $group_count = 32;
        $title = 'CASH DISBURSEMENT BOOK SUNDRIES';



        $pdf= \App::make('dompdf.wrapper');
        //$pdf->setPaper([0,0,612.00,936.00],'landscape');
        $pdf->setPaper('legal','portrait');
        $pdf->loadView('accounting.Disbursement.Book.printsundries',compact('title','from','to','group_count'));
        //return view('accounting.Disbursement.Book.printsundries',compact('title','from','to','group_count'));
        return $pdf->stream();
    }
       
    function disbursementbookexcel($from,$trandate){

        $sundries = \App\Accounting::where('type',4)->where('isfinal',1)->where('isreversed',0)->whereBetween('transactiondate',array($from,$trandate))->get();
        $entries = \App\RptDisbursementBook::where('idno',\Auth::user()->idno)->get();
           
        $name = "Disbursement Book from ".$from." to ".$trandate;
           
        Excel::create($name, function($excel) use($sundries,$entries,$from,$trandate) {
            $excel->sheet('Book', function($sheet) use($entries,$from,$trandate){
                    $sheet->loadView('accounting.Disbursement.Book.downloadbook')
                            ->with('entries',$entries)
                            ->with('from',$from)
                            ->with('trandate',$trandate)
                            ->setFontSize(10)
                            ->setAutoSize(true);
                    
                            $sheet->mergeCells('A1:A2');
                            $sheet->mergeCells('B1:B2');
                            $sheet->mergeCells('C1:C2');
                            $sheet->mergeCells('D1:D2');
                            $sheet->mergeCells('E1:E2');
                            $sheet->mergeCells('F1:F2');
                            $sheet->mergeCells('G1:G2');
                            $sheet->mergeCells('H1:H2');
                            $sheet->mergeCells('I1:I2');
                            $sheet->mergeCells('J1:J2');
                            $sheet->mergeCells('K1:K2');
                            $sheet->mergeCells('L1:N1');
                            $sheet->mergeCells('O1:O2');
                    $currentrow = 3;
                    foreach($entries as $entry){
                        if($entry->row_count > 1){
                            
                            $sheet->mergeCells('A'.$currentrow.':A'.($currentrow+($entry->row_count-1)));
                            $sheet->mergeCells('B'.$currentrow.':B'.($currentrow+($entry->row_count-1)));
                            $sheet->mergeCells('C'.$currentrow.':C'.($currentrow+($entry->row_count-1)));
                            $sheet->mergeCells('D'.$currentrow.':D'.($currentrow+($entry->row_count-1)));
                            $sheet->mergeCells('E'.$currentrow.':E'.($currentrow+($entry->row_count-1)));
                            $sheet->mergeCells('F'.$currentrow.':F'.($currentrow+($entry->row_count-1)));
                            $sheet->mergeCells('G'.$currentrow.':G'.($currentrow+($entry->row_count-1)));
                            $sheet->mergeCells('H'.$currentrow.':H'.($currentrow+($entry->row_count-1)));
                            $sheet->mergeCells('I'.$currentrow.':I'.($currentrow+($entry->row_count-1)));
                            $sheet->mergeCells('J'.$currentrow.':J'.($currentrow+($entry->row_count-1)));
                            $sheet->mergeCells('K'.$currentrow.':K'.($currentrow+($entry->row_count-1)));
                            
                            
                        }
                        $currentrow = $currentrow+$entry->row_count;
                    }
                    
                    $sheet->setColumnFormat(array(
                                'C'=> '#,##0.00',
                                'D'=> '#,##0.00',
                                'E'=> '#,##0.00',
                                'F'=> '#,##0.00',
                                'G'=> '#,##0.00',
                                'H'=> '#,##0.00',
                                'I'=> '#,##0.00',
                                'J'=> '#,##0.00',
                                'K'=> '#,##0.00',
                                'L'=> '#,##0.00',
                                'M'=> '#,##0.00',
                                
                            ));
            });
            
            $excel->sheet('Sundries', function($sheet){
                $sheet->loadView('accounting.Disbursement.Book.downloadsundries');
            });
            
        })->export('xlsx');
           
    }
       
    static function customChunk($records,$size){
        $chunks = array();
        $currntrows = 0;
        $group = array();
        
        foreach($records  as $items){
            if($size < $currntrows+$items->row_count || ($items ==$records->last())){
               $chunks[] = collect($group);
               unset($group);
               $currntrows = 0;
            }
            
            $currntrows = $currntrows+$items->row_count;
            $group[] = $items;
        }
        
        return collect($chunks);
    }
    
   function processdisbursementbook($disbursements,$totalindic){
        foreach($disbursements as $disbursement){
            $acctentry = \App\Accounting::where('refno',$disbursement->refno)->where('type','4')->get();
            $debits = $acctentry->where('cr_db_indic',0,false);
            $credits = $acctentry->where('cr_db_indic',1,false);
 
            $addrpt = new \App\RptDisbursementBook;
            $addrpt->idno = \Auth::user()->idno;
            $addrpt->payee = $disbursement->payee;
            $addrpt->transactiondate = $disbursement->transactiondate;
            $addrpt->voucherno = $disbursement->voucherno;
            $addrpt->refno = $disbursement->refno;
            $addrpt->isreverse = $disbursement->isreverse;
            $addrpt->totalindic = $totalindic;
            $addrpt->voucheramount = $credits->whereIn('accountcode',array('110012','110013','110014','110015','110016','110017','110018','110019','110020','110021'))->sum('credit');
            $addrpt->advances_employee = $debits->where('accountcode','120103',false)->sum('debit');
            $addrpt->cost_of_goods = $debits->whereIn('accountcode',array('440601','440701'))->sum('debit');
            $addrpt->instructional_materials = $debits->where('accountcode','580000',false)->sum('debit');
            $addrpt->salaries_allowances = $debits->where('accountcode','500000',false)->sum('debit');
            $addrpt->personnel_dev = $debits->where('accountcode','500500',false)->sum('debit');
            $addrpt->other_emp_benefit = $debits->where('accountcode','500300',false)->sum('debit');
            $addrpt->office_supplies = $debits->where('accountcode','120120',false)->sum('debit');
            $addrpt->travel_expenses = $debits->where('accountcode','590200')->sum('debit');

            $row_count = $this->processsundries($acctentry);
            $addrpt->row_count = $row_count;


           //Debits
            $sundry_debit = $debits->filter(function($item){
               return !in_array(data_get($item, 'accountcode'), array('120103','440601','440701','580000','500000','500500','500300','120201','590200'));                   
                });
            $addrpt->sundry_debit = $sundry_debit->sum('debit');

           //Credits
            $sundry_credit = $credits->filter(function($item){
                return !in_array(data_get($item, 'accountcode'), array('110012','110013','110014','110015','110016','110017','110018','110019','110020','110021'));
                });
            $addrpt->sundry_credit = $sundry_credit->sum('credit'); 

            $addrpt->save();
        }

    }
       
    function processsundries($acctentry){
        $rowcount = 0;
        $entries = $acctentry->groupBy("accountcode");

        foreach($entries as $entry){
            $debit = 0;
            $credit = 0;
            $sundries = new \App\RptDisbursementBookSundries();
            $sundries->idno = \Auth::user()->idno;
            $sundries->refno = $entry->pluck('refno')->last();
            $sundries->particular = $entry->pluck('accountname')->last();
            $sundries->accountingcode = $entry->pluck('accountcode')->last();

            if(!in_array($entry->pluck('accountcode')->last(), array('120103','440601','440701','580000','500000','500500','500300','120201','590200'))){
                $debit = $entry->sum('debit');
            }

            if(!in_array($entry->pluck('accountcode')->last(), array('110012','110013','110014','110015','110016','110017','110018','110019','110020','110021'))){
                $credit = $entry->sum('credit');
            }

            if($debit != 0 || $credit != 0 ){
                $sundries->debit = $debit;
                $sundries->credit = $credit;
                $sundries->save();
                $rowcount++;

            }

        }
        if($rowcount > 0){
            return $rowcount;
        }else{
            return 1;
        }

    }

}
