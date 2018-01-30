<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DisbursementController extends Controller
{
    
    public function __construct()
	{
		$this->middleware('auth');
	}
    //
        
        function adddisbursement(){
            return view('accounting.adddisbursement');
        }
        
       function printdisbursement($refno){
           return view('accounting.printdisbursement',compact('refno'));
       }
       function restorecanceldisbursement($kind,$refno){
           if($kind=="Cancel"){
               $cr = 1;
            } else {
                $cr = 0;
            }
             \App\Credit::where('refno',$refno)->update(array('isreverse' => $cr));
             \App\Dedit::where('refno',$refno)->update(['isreverse'=> $cr]);
             \App\Accounting::where('refno',$refno)->update(['isreversed'=>$cr]);
             \App\Disbursement::where('refno',$refno)->update(['isreverse'=>$cr]);
             return redirect(url("printdisbursement",$refno));    
       }
       
       function printcheckdetails($refno){
           $disbursement = \App\Disbursement::where('refno',$refno)->first();
           $payee = $disbursement->payee;
           $amount=$disbursement->amount;
           $date=$disbursement->transactiondate;
           $amountinwords=$this->convert_number_to_words($amount);
           $voucherno = $disbursement->voucherno;
           $checkno = $disbursement->checkno;
           $pdfprint = \App::make('dompdf.wrapper');
           $pdfprint->setPaper([0,0,1092,570],'portrait');
           $pdfprint->loadView('print.printcheckdetailpdf',compact('payee','amount','date','amountinwords','voucherno','checkno'));
           return $pdfprint->stream();
       }
       
       
       function printcheckvoucher($refno){
           $disbursement=  \App\Disbursement::where('refno',$refno)->first();
           $accountings = \App\Accounting::where('refno',$refno)->get();
           $amountinwords = $this->convert_number_to_words($disbursement->amount);
           $pdf = \App::make('dompdf.wrapper');
           $pdf->setPaper([0,0,1092,570],'portrait');
           $pdf->loadView('print.printcheckvoucher',compact('disbursement','accountings','amountinwords'));
           return $pdf->stream();
       }
       function dailydisbursementlist($trandate){
           return view('accounting.dailydisbursementlist',compact('trandate'));
       }
       function dailydisbursementalllist($fromdate,$todate){
           return view('accounting.dailydisbursementalllist',compact('fromdate','todate'));
       }
       function printdisbursementlistpdf($trandate){
           $pdf = \App::make('dompdf.wrapper');
           $pdf->setPaper("Letter","portrait");
           $pdf->loadView('print.printdisbursementlistpdf',compact('trandate'));
           return $pdf->stream();
       }
       function disbursement($from,$trandate){
           $rangereports = DB::Select("Select distinct refno from accountings where type = '4' and transactiondate BETWEEN ('$from','$trandate')");
           return view("accounting.disbursement",compact('rangereports','trandate'));
       }
       
       function processdisbursementbook($disbursements,$totalindic){
           foreach($disbursements as $disbursement){
               $acctentry = \App\Accounting::where('refno',$disbursement->refno)->where('type','4')->get();
               $debits = $acctentry->where('cr_db_indic',0);
               $credits = $acctentry->where('cr_db_indic',1);
               
               $addrpt = new \App\RptDisbursementBook;
               $addrpt->idno = \Auth::user()->idno;
               $addrpt->payee = $disbursement->payee;
               $addrpt->transactiondate = $disbursement->transactiondate;
               $addrpt->voucherno = $disbursement->voucherno;
               $addrpt->refno = $disbursement->refno;
               $addrpt->isreverse = $disbursement->isreverse;
               $addrpt->totalindic = $totalindic;
               $addrpt->advances_employee = $debits->where('accountcode','120103')->sum('debit');
               $addrpt->cost_of_goods = $debits->whereIn('accountcode',array('440601','440701'))->sum('debit');
               $addrpt->instructional_materials = $debits->where('accountcode','580000')->sum('debit');
               $addrpt->salaries_allowances = $debits->where('accountcode','500000')->sum('debit');
               $addrpt->personnel_dev = $debits->where('accountcode','500500')->sum('debit');
               $addrpt->other_emp_benefit = $debits->where('accountcode','500300')->sum('debit');
               $addrpt->office_supplies = $debits->where('accountcode','120120')->sum('debit');
               $addrpt->travel_expenses = $debits->where('accountcode','590200')->sum('debit');
               $addrpt->sundry_debit = $debits->filter(function($item){
                   return !in_array(data_get($item, 'accountcode'), array('120103','440601','440701','580000','500000','500500','500300','120201','590200'));                   
                    })->sum('debit');
                    
               $addrpt->voucheramount = $credits->whereIn('accountcode',array('110012','110013','110014','110015','110016','110017','110018','110019','110020','110021'))->sum('credit');
               $addrpt->sundry_credit = $credits->filter(function($item){
                   return !in_array(data_get($item, 'accountcode'), array('110012','110013','110014','110015','110016','110017','110018','110019','110020','110021'));
                   })->sum('credit');
               
               $addrpt->save();
           }
           
       }
       
       function disbursementbook($from,$trandate){
           
           \App\RptDisbursementBook::where('idno', \Auth::user()->idno)->delete();
           $disbursements = \App\Disbursement::whereBetween('transactiondate', array($from,$trandate))->get();
           $this->processdisbursementbook($disbursements,"0");

           $sundries = \App\Accounting::where('type',4)->where('isfinal',1)->where('isreversed',0)->whereBetween('transactiondate',array($from,$trandate))->get();
           $entries = \App\RptDisbursementBook::where('idno',\Auth::user()->idno)->get();
           
           return view('accounting.Disbursement.Book.book',compact('from','trandate','sundries','entries'));
           //return view('accounting.disbursementbook',compact('trandate','sundries'));
       }
       
       function printdisbursementpdf($from,$to){
            $sundries = \App\Accounting::where('type',4)->where('isfinal',1)->where('isreversed',0)->whereBetween('transactiondate',array($from,$to))->get();
            $entries = \App\RptDisbursementBook::where('idno',\Auth::user()->idno)->get();
            $trandate = $to;
            $title = 'CASH DISBURSEMENT BOOK';
            
            $pdf= \App::make('dompdf.wrapper');
            $pdf->setPaper('folio','landscape');
            $pdf->loadView('accounting.Disbursement.Book.printbook',compact('title','from','to','entries','sundries'));
            //$pdf->loadView('accounting.printdisbursementpdf',compact('trandate'));
            return $pdf->stream();
           
       }
       
    function convert_number_to_words($number) {
    $hyphen      = ' ';
    $conjunction = ' ';
    $separator   = ' ';
    $negative    = 'negative ';
    $decimal     = ' ';
    $dictionary  = array(
        0                   => ' ',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Forty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . $this->convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= $this->convert_number_to_words($remainder);
            }
            break;
    }
        
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        /*
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words); 
        */
        if($fraction !="00"){
//        $myArray = str_split((string) $fraction);
//        $string .= " Pesos and " . $dictionary[$myArray[0]*10] . " " . $dictionary[$myArray[1]] . " Centavos";
            if(intval($fraction) <=19){
                $string .= " Pesos and " .  $dictionary[intval($fraction)]  . " Centavos";   
            }else{
                $myArray = str_split((string) $fraction);
                $string .= " Pesos and " . $dictionary[$myArray[0]*10] . " " . $dictionary[$myArray[1]] . " Centavos";   
            }
        } else{
        $string.= " Pesos ";
        }
    }

    return $string;
}

    function checkSummary($from,$to){
        $accounts = DB::Select("Select DISTINCT bank from disbursements order by bank asc");
        
        return view('accounting.checkdisbursement',compact('from','to','accounts'));
    }
    
    function printcheckSummary($from,$to){
        $accounts = DB::Select("Select DISTINCT bank from disbursements order by bank asc");
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('print.checkdisbursement',compact('from','to','accounts')); 
        return $pdf->stream();  
    }

    function searchvoucher(){
        return view('accounting.searchvoucher');
    }
    
    function findvoucher(request $request){
        $disbursement = \App\Disbursement::where('voucherno',$request->voucher)->get();
        $refno = 0;
        
        if(count($disbursement)>1){
            return view('accounting.vouchersearchlist',compact('disbursement'));
        }else{
            foreach($disbursement as $disb){
                $refno = $disb->refno;
            }
            return redirect('printdisbursement/'.$refno);
        }
    }  

    function searchpayee(){
        $payees = \App\Disbursement::distinct('payee')->pluck('payee')->toArray();
        
        foreach($payees as $key=>$value){
          $payees[$key]=str_replace('"','\"',$value);
        }
        return view('accounting.searchPayee',compact('payees'));
    }
    
    function findpayee(request $request){
        $disbursement = \App\Disbursement::where('payee',$request->payee)->get();

        return view('accounting.vouchersearchlist',compact('disbursement'));
    }
    
    function editDisbursement(Request $request){
        $field = $request->editfields;
        $value = $request->value;
        $refno = $request->voucher;
        
        $dm = \App\DisbursementController::where('refno',$refno)->first();
        $dm->$field = $value;
        $dm->save();
    }
}
