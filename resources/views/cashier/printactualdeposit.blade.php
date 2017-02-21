<html>
    <body>
        <span>Don Bosco Technical Institute - Makati <br>
            Actual Deposit Report</br>
            Teller : {{\Auth::user()->firstname}} {{\Auth::user()->lastname}}</span>
        
       <br>
       
       <?php
    $cbcash=0;
    $cbcheck=0;
    $bpi1cash=0;
    $bpi1check=0;
    $bpi2cash=0;
    $bpi2check=0;
    $totalactual=0;
    ?>

   <h4>Transaction Date: {{date('M d, Y',strtotime($transactiondate))}}</h4>
     
    
    
             
                <h5>Deposit Slip Breakdown</h5>
                    @if(count($deposit_slips)>0)
                        <table cellspacing="0" cellpadding ="0" border="0" width="70%"><tr><td>Bank</td><td>Deposit Type</td><td align="center">Amount</td></tr>
 
                            @foreach($deposit_slips as $deposit_slip)
                            <?php $totalactual = $totalactual + $deposit_slip->amount;
                            if($deposit_slip->bank == "China Bank"){
                                if($deposit_slip->deposittype == '0'){
                                    $cbcash = $cbcash + $deposit_slip->amount;
                                } else {
                                    $cbcheck = $cbcheck + $deposit_slip->amount;
                                }
                            } elseif ($deposit_slip->bank=="BPI 1") {
                                if($deposit_slip->deposittype == '0'){
                                    $bpi1cash = $bpi1cash + $deposit_slip->amount;
                                } else {
                                    $bpi1check=$bpi1check + $deposit_slip->amount;
                                }
                        } elseif ($deposit_slip->bank == "BPI 2") {
                            if($deposit_slip->deposittype == '0'){
                                $bpi2cash = $bpi2cash + $deposit_slip->amount;
                            } else {
                                $bpi2check = $bpi2check = $deposit_slip->amount;
                            }
                        
                    }
                            ?>
                                <tr><td>{{$deposit_slip->bank}}</td><td>
                                    @if($deposit_slip->deposittype=="0")
                                        Cash
                                    @else
                                        Check
                                    @endif
                                </td><td align="right">{{number_format($deposit_slip->amount,2)}}</td></tr>
                            @endforeach
                        </table>
                    @else
                        <h5>No Deposit slip Created Yet</h5>
                    @endif
    <br><br>
                    <h5>Actual Deposit Total</h5>
                    <table cellspacing="0" cellpadding ="0" border="0" width="90%">
                    <tr><td>Bank</td><td align="right">Cash</td><td align="right">Check</td><td align="right">Total</td></tr>
                    <tr><td>China Bank</td><td align="right">{{number_format($cbcash,2)}}</td><td align="right">{{number_format($cbcheck,2)}}</td><td align="right">{{number_format($cbcash+$cbcheck,2)}}</td></tr>
                    <tr><td>BPI 1</td><td align="right">{{number_format($bpi1cash,2)}}</td><td align="right">{{number_format($bpi1check,2)}}</td><td align="right">{{number_format($bpi1cash+$bpi1check,2)}}</td></tr>
                    <tr><td>BPI 2</td><td align="right">{{number_format($bpi2cash,2)}}</td><td align="right">{{number_format($bpi2check,2)}}</td><td align="right">{{number_format($bpi1cash+$bpi2check,2)}}</td></tr>
                    
                    </table>
                        
    
      <hr>
    <h5>Computed receipts</h5>
        <table class="table table-striped" width="90%"><tr><td>Bank</td><td align="right">Cash</td><td align="right">Check</td><td align="right">Total</td></tr>
        @if(count($debits)>0)
            @foreach($debits as $debit)
            <tr><td>{{$debit->depositto}}</td><td align="right">{{number_format($debit->amount,2)}}</td>
            <td align="right">{{number_format($debit->checkamount,2)}}</td>
            <td align="right">{{number_format($debit->amount+$debit->checkamount,2)}}</td></tr>
            @endforeach
        @else
            <tr><td colspan="4"> No Collection Yet!!..</td></tr>
        @endif
        </table>
    

    <hr>
    <h5>Computed Receipt vs Actual Deposit</h5>
    <table class="table table-striped" width = "90%">
        <tr><td><b>Total Computed Receipts</b></td><td align="right"><b>{{number_format($totaldebit,2)}}</b></td></tr>
        <tr><td><b>Total Actual Deposit</b></td><td align="right"><b>{{number_format($totalactual,2)}}</b></td></tr>
        <tr><td><b>Overall Difference</b></td><td align="right"><b>{{number_format($totaldebit-$totalactual,2)}}</b></td></tr>
    </table>    
  
    <hr>
    <h5>Encashment</h5>
        <table width="905">
            <tr><td>Bank</td><td>Amount</td></tr>
                @if(count($encashments)=='0')
                    <tr><td colspan="2">No Encashment Yet!!..</td></tr>
                @else
                    @foreach($encashments as $encashment)
                        <tr><td>{{$encashment->whattype}}</td><td align="right">{{number_format($encashment->amount,2)}}</td></tr>
                    @endforeach
                @endif
        </table>

       
        
    </body>
    
</html>
