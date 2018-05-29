<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReceiptController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
   static function viewreceipt($refno, $idno = 0){
       $student = \App\User::where('idno',$idno)->first();
       $status= \App\Status::where('idno',$idno)->first();
       $debits = \App\Dedit::where('refno',$refno)->get();
       $debit_discount = \App\Dedit::where('refno',$refno)->where('paymenttype','4')->get();
       $debit_reservation = \App\Dedit::where('refno',$refno)->where('paymenttype','5')->first();
       $debit_fape = \App\Dedit::where('refno',$refno)->where('paymenttype','7')->first();
       $debit_deposit = \App\Dedit::where('refno',$refno)->where('paymenttype','8')->first();
       $debit_awards = \App\Dedit::where('refno',$refno)->where('paymenttype','9')->get();
       $debit_cash = \App\Dedit::where('refno',$refno)->where('paymenttype','1')->first();
       $debit_dm = \App\Dedit::where('refno',$refno)->where('paymenttype','3')->first();
       $credits = DB::Select("select sum(amount) as amount, receipt_details, transactiondate, sub_department from credits "
               . "where refno = '$refno' group by receipt_details, transactiondate");
       $creditbreakdowns = DB::Select("select description,sum(amount) as amount, receipt_details, transactiondate, sub_department from credits "
               . "where refno = '$refno' group by description,sub_department order by receipt_details");
       $timeissued =  \App\Credit::where('refno',$refno)->first();
       $timeis=date('h:i:s A',strtotime($timeissued->created_at));
       $tdate = \App\Dedit::where('refno',$refno)->first();
       $posted = \App\User::where('idno',$tdate->postedby)->first();
       return view("cashier.viewreceipt",compact('posted','timeis','tdate','student','debits','credits','status','debit_discount','debit_reservation','debit_cash','debit_dm','idno','refno','debit_fape','debit_deposit','creditbreakdowns','debit_awards'));
   }
   
    function printreceipt($refno, $idno = 0){
       $student = \App\User::where('idno',$idno)->first();
       $status= \App\Status::where('idno',$idno)->first();
       $debits = \App\Dedit::where('refno',$refno)->get();
       $debit_discount = \App\Dedit::where('refno',$refno)->where('paymenttype','4')->get();
       $debit_reservation = \App\Dedit::where('refno',$refno)->where('paymenttype','5')->first();
       $debit_fape = \App\Dedit::where('refno',$refno)->where('paymenttype','7')->first();
       $debit_deposit = \App\Dedit::where('refno',$refno)->where('paymenttype','8')->first();
       $debit_awards = \App\Dedit::where('refno',$refno)->where('paymenttype','9')->get();
       $debit_cash = \App\Dedit::where('refno',$refno)->where('paymenttype','1')->first();
       $debit_check = \App\Dedit::where('refno',$refno)->where('paymenttype','2')->first();
       $debit_dm = \App\Dedit::where('refno',$refno)->where('paymenttype','3')->first();
       $credits = DB::Select("select sum(amount) as amount, receipt_details, transactiondate, sub_department from credits "
               . "where refno = '$refno' group by receipt_details, transactiondate");
       $timeissued =  \App\Credit::where('refno',$refno)->first();
       $timeis=date('h:i:s A',strtotime($timeissued->created_at));
       $tdate = \App\Dedit::where('refno',$refno)->first();
       $posted = \App\User::where('idno',$tdate->postedby)->first();
       
       $pdf = \App::make('dompdf.wrapper');
       $pdf->setPaper([0, 0, 336, 440], 'portrait');
       $pdf->loadView("cashier.printreceipt",compact('posted','timeis','tdate','student','debits','credits','status','debit_discount','debit_reservation','debit_cash','debit_dm','idno','refno','debit_fape','debit_deposit','debit_awards'));
       return $pdf->stream();
    }
    
   static function receiptOverview($refno){
       $debits = \App\Dedit::where('refno',$refno)->get();
       $debit_discount =    $debits->where('paymenttype','4',false)->sum('amount');
       $debit_reservation = $debits->where('paymenttype','5',false)->sum('amount');
       $debit_fape =        $debits->where('paymenttype','7',false)->sum('amount');
       $debit_deposit =     $debits->where('paymenttype','8',false)->sum('amount');
       $debit_awards =      $debits->where('paymenttype','9',false);
       $debit_cash =        $debits->where('paymenttype','1',false)->first();
       $debit_dm =          $debits->where('paymenttype','3',false)->sum('amount');
       $credits = DB::Select("select sum(amount) as amount, receipt_details, transactiondate, sub_department from credits "
               . "where refno = '$refno' group by receipt_details, transactiondate");
       
       $timeis=date('h:i:s A',strtotime($debits->first()->created_at));
       $tdate = $debits->first();
       $posted = \App\User::where('idno',$tdate->postedby)->first();
       $idno = $debits->first()->idno;
       $schoolyear = $debits->first()->schoolyear;
       
       return view("accounting.CashReceipt.Receipt.receiptOverview",compact('idno','posted','tdate','timeis','schoolyear','credits','debit_discount','debit_reservation','debit_fape','debit_deposit','debit_awards','debit_cash','debit_dm','debits'))->render();
   }
    
}
