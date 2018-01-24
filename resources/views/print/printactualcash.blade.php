<?php
       
        $acbccash = 0;
        $acbccheck = 0;
        $abpi1cash = 0;
        $abpi1check = 0;
        $abpi2cash=0;
        $abpi2check=0;
        $variance = 0;
        if(count($actual)>0){
        $acbccash = $actual->cbccash;
        $acbccheck = $actual->cbccheck;
        $abpi1cash = $actual->bpi1cash;
        $abpi1check = $actual->bpi1check;
        $abpi2cash=$actual->bpi2cash;
        $abpi2check=$actual->bpi2check;
        $variance = $actual->variance;
        }
        ?>
<h3>Don Bosco Technical Institute of Makati, Inc</h3>
<p>Actual Cash Deposit<br>
   on {{date('M d, Y',strtotime($transactiondate))}} <br>
   </p>
   <h3>Actual Receipt</h3>
   <table width="90%">
   <tr><td>Bank</td><td>Check</td><td>Cash</td><td>Encashment</td><td>Total</td></tr>
   <tr><td>China Bank</td><td align="right">{{number_format($cbcheck,2)}}</td><td align="right">{{number_format($cbcash,2)}}</td><td>{{number_format($encashcbc,2)}}</td><td>{{number_format($cbcash+$cbcheck-$encashcbc,2)}}</td></tr>
   <tr><td>BPI 1</td><td align="right">{{number_format($bpi1check,2)}}</td><td align="right">{{number_format($bpi2cash,2)}}</td><td>{{number_format($encashbpi1,2)}}</td><td>{{number_format($bpi1cash+$bpi1check-$encashbpi1,2)}}</td></tr>
   <tr><td>BPI 2</td><td align="right">{{number_format($bpi2check,2)}}</td><td align="right">{{number_format($bpi2cash,2)}}</td><td>{{number_format($encashbpi2,2)}}</td><td>{{number_format($bpi2cash+$bpi2check-$encashbpi2,2)}}</td></tr>
   </table>
   <h3>Actual Cash</h3>
   <table width="90%">
   <tr><td></td>
       <td>CBC Cash</td><td>CBC Check</td><td>CBC Encashment</td>
       <td>BPI1 Cash</td><td>BPI1 Check</td><td>BPI1 Encashment</td>
       <td>BPI2 Cash</td><td>BPI2 Check</td><td>BPI2 Encashment</td>
       <td>Variance</td></tr>
   @foreach($actual as $ac)
   <tr><td>{{$ac->cashcbc}}</td></tr>
   @endforeach
   
   </table>
   