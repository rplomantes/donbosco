<?php

namespace App\Http\Controllers\Administrator\Receipt;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Cashier\ReceiptController;

class UpdateReceipt extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    function searchRef(){
        return view('Administrator.CashReceipt.ChangeCashReceipt.searchReceipt');
    }
    
    function searchResult(Request $request){
        $account = \App\Dedit::where('refno',$request->refno)->orderBy('id','ASC')->first();
        
        if($account){
            return redirect(route('admin.viewreceipt',$account->refno));
        }else{
            return redirect()->back();
        }
        
    }
    
    function showReceipt($refno){
        
        return view('Administrator.CashReceipt.ChangeCashReceipt.resultIndex',compact('refno'));
    }
    
    function updateReceipt(Request $request){
        $debits = \App\Dedit::where('refno',$request->refno)->orderBy('id','ASC')->get();
        $details = $debits->first();
        $cash = $debits->where('paymenttype',1,false)->first();
        
        if(strcmp($details->receiptno, $request->receiptno) != 0){
            $this->changeReceiptNo($request->refno,$request->receiptno);
        }
        
        if(strcmp($details->remarks, $request->remarks) != 0){
            $debits->remarks = $request->remarks;
            $debits->save();
        }
        
        
        if($cash){
            if(strcmp($details->depositto, $request->depositto) != 0){
                
            }
        }
        
        $request->has('depositto');
    }
    
    private function changeReceiptNo($refno,$orNo){
        \App\Credit::where('refno',$refno)->update(['receiptno'=>$orNo]);
        \App\Dedit::where('refno',$refno)->update(['receiptno'=>$orNo]);
    }
    
    private function changeDepositTo($refno,$depositto){
        $debits = \App\Dedit::where('refno',$refno)->where('paymenttype',1)->first();
        
        if($debits && ($debits->checkamount > 0 || $debits->amount > 0)){
            
        }
        
    }
    
    static function updateForm($refno){
        $debits = \App\Dedit::where('refno',$refno)->orderBy('id','ASC')->get();
        $details = $debits->first();
        $cash = $debits->where('paymenttype',1,false)->first();
        return view('Administrator.CashReceipt.ChangeCashReceipt.updateForm',compact('details','cash'));
    }
}
