<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Excel;

class DisbursementController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }
    //
        
    function adddisbursement(){
        return view('accounting.adddisbursement');
    }
        
    function printdisbursement($refno){
        $accountings = \App\Accounting::where('refno',$refno)->get();
        $disbursement = \App\Disbursement::where('refno',$refno)->first();
        
        $particular = $this->read_escapeString($disbursement->remarks);
        $payee = $this->read_escapeString($disbursement->payee);
        return view('accounting.printdisbursement',compact('refno','accountings','disbursement','particular','payee'));
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
           //$pdf->setPaper([0,0,612.00,936.00],'landscape');
           $pdf->setPaper("letter","portrait");
           $pdf->loadView('print.printdisbursementlistpdf',compact('trandate'));
           return $pdf->stream();
       }
       function disbursement($from,$trandate){
           $rangereports = DB::Select("Select distinct refno from accountings where type = '4' and transactiondate BETWEEN ('$from','$trandate')");
           return view("accounting.disbursement",compact('rangereports','trandate'));
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
        $value = $this->write_escapeString($request->values);
        $refno = $request->voucher;
        
        $dm = \App\Disbursement::where('refno',$refno)->first();
        $dm->$field = $value;
        $dm->save();
    }
}
