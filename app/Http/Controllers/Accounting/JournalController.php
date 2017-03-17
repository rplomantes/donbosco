<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JournalController extends Controller
{
    //
      public function __construct()
	{
		$this->middleware('auth');
	}
        
      function ShowJournalList(){
          return view('accounting.journallist');
      } 
      
        function addEntry(){
        return view("accounting.addentry");
        }
      
        function printjournalvoucher($referenceid){
            return view('accounting.printjournalvoucher',compact('referenceid'));
        }
    
        function dailyjournallist($trandate){
            //$trandate = date('Y-m-d',strtotime(Carbon::now()));
            return view('accounting.dailyjournallist',compact('trandate'));
        }
        function restorecanceljournal($kind, $refno){
            if($kind=="Cancel"){
               $cr = 1;
            } else {
                $cr = 0;
            }
             \App\Credit::where('refno',$refno)->update(array('isreverse' => $cr));
                \App\Dedit::where('refno',$refno)->update(['isreverse'=> $cr]);
                \App\Accounting::where('refno',$refno)->update(['isreversed'=>$cr]);
                \App\AccountingRemark::where('refno',$refno)->update(['isreverse'=>$cr]);
          return redirect(url("printjournalvoucher",$refno));      
        }
        function printpdfjournalvoucher($refno){
           $pdfprint = \App::make('dompdf.wrapper');
           $pdfprint->setPaper('Letter','portrait');
           $pdfprint->loadView('print.printjournalvoucher',compact('refno'));
           return $pdfprint->stream();
        }
}
