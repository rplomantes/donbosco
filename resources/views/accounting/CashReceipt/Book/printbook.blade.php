<?php
ini_set('memory_limit', '256M');
$forwarded = $records->where('refno','forwarded');
        
$receipts = $records->where('transactiondate',$transactiondate);

$grand_total = $records->where('isreverse',0);

$sundries = $credits->where('isreverse',0)->where('transactiondate',$transactiondate)->filter(function($item){
                    return !in_array(data_get($item, 'accountingcode'), array('420200','420400','440400','420100','420000','120100','410000','210400'));
                    });
$b_cash = 0;
$b_discount = 0;
$b_fape = 0;
$b_dreservation = 0;
$b_deposit = 0;
$b_elearning = 0;
$b_misc = 0;
$b_book = 0;
$b_dept = 0;
$b_reg = 0;
$b_tuition = 0;
$b_creservation = 0;
$b_csundry = 0;

                    
$chunk = $receipts->chunk(18);

?>
<html>
    <head>
        <style>
            .payee{
                width:120px;
                white-space: nowrap;
                text-align: left
            }
            td,th{
                font-size: 9.5pt;
            }
            .report{
                page-break-after: always
            }
        </style>
    </head>
    <body>
@include('inludes.header')
        @foreach($chunk as $group)
        <table class="report" border="1" cellspacing="0" cellpadding="2" width="100%">
            <tr>
                <th>Receipt No.</th>
                <th>Name</th>
                <th>Debit<br>Cash / Check</th>
                <th>Debit<br>Discount</th>
                <th>Debit<br>FAPE</th>
                <th>Debit<br>Reservation</th>
                <th>Debit<br>Deposit</th>
                <th>E - Learning</th>
                <th>Misc</th>
                <th>Book</th>
                <th>Department<br> Facilities</th>
                <th>Registration Fee</th>
                <th>Tuition Fee</th>
                <th>Credit<br>Reservation</th>
                <th>Credit<br>Sundry</th>
                <th>Sundry<br>Accounts</th>
                <th>Status</th>
            </tr>
            
            @if($group == $chunk->first())
            <tr align='right'>
                <td colspan="2" style='text-align: left'>Forwarding Balance ({{date("M",strtotime($transactiondate))}})</td>
                <td>{{number_format($forwarded->sum('cash'),2)}}</td>
                <td>{{number_format($forwarded->sum('discount'),2)}}</td>
                <td>{{number_format($forwarded->sum('fape'),2)}}</td>
                <td>{{number_format($forwarded->sum('dreservation'),2)}}</td>
                <td>{{number_format($forwarded->sum('deposit'),2)}}</td>
                
                <td>{{number_format($forwarded->sum('elearning'),2)}}</td>
                <td>{{number_format($forwarded->sum('misc'),2)}}</td>
                <td>{{number_format($forwarded->sum('book'),2)}}</td>
                <td>{{number_format($forwarded->sum('dept'),2)}}</td>
                <td>{{number_format($forwarded->sum('registration'),2)}}</td>
                <td>{{number_format($forwarded->sum('tuition'),2)}}</td>
                <td>{{number_format($forwarded->sum('creservation'),2)}}</td>
                <td>{{number_format($forwarded->sum('csundry'),2)}}</td>
                <td></td>
                <td></td>
            </tr>
            <tr><td colspan='17'></td></tr>
            @else
            <tr align='right'>
                <td colspan="2" style='text-align: left'>Balance Brought Forward</td>
                <td>{{number_format($b_cash,2)}}</td>
                <td>{{number_format($b_discount,2)}}</td>
                <td>{{number_format($b_fape,2)}}</td>
                <td>{{number_format($b_dreservation,2)}}</td>
                <td>{{number_format($b_deposit,2)}}</td>
                <td>{{number_format($b_elearning,2)}}</td>
                <td>{{number_format($b_misc,2)}}</td>
                <td>{{number_format($b_book,2)}}</td>
                <td>{{number_format($b_dept,2)}}</td>
                <td>{{number_format($b_reg,2)}}</td>
                <td>{{number_format($b_tuition,2)}}</td>
                <td>{{number_format($b_creservation,2)}}</td>
                <td>{{number_format($b_csundry,2)}}</td>
                <td></td>
                <td></td>
            </tr>
            @endif
            
            @foreach($group as $receipt)
            <tr style='text-align: right'>
                <td style='text-align: left'>{{$receipt->receiptno}}</td>
                <td style='text-align: left'>
                    <div class="payee">
                        @if(strlen($receipt->from)>20)
                        {{substr($receipt->from, 0 , 20)}}...
                        @else 
                        {{$receipt->from}}
                        @endif
                    </div>
                </td>
                
                <td>{{number_format($receipt->cash,2)}}</td>
                <td>{{number_format($receipt->discount,2)}}</td>
                <td>{{number_format($receipt->fape,2)}}</td>
                <td>{{number_format($receipt->dreservation,2)}}</td>
                <td>{{number_format($receipt->deposit,2)}}</td>
                
                <td>{{number_format($receipt->elearning,2)}}</td>
                <td>{{number_format($receipt->misc,2)}}</td>
                <td>{{number_format($receipt->book,2)}}</td>
                <td>{{number_format($receipt->dept,2)}}</td>
                <td>{{number_format($receipt->registration,2)}}</td>
                <td>{{number_format($receipt->tuition,2)}}</td>
                <td>{{number_format($receipt->creservation,2)}}</td>
                <td>{{number_format($receipt->csundry,2)}}</td>
                <td align="left" style="white-space: nowrap">{!!$receipt->csundry_account!!}</td>
                <td align='center'>
                    @if($receipt->isreverse == 0)
                    OK
                    @else
                    Cancelled
                    @endif
                </td>
            </tr>
            @endforeach
            <?php
            $subtotal = $receipts->where('isreverse',0);
            $b_cash = $b_cash+$subtotal->sum('cash');
            $b_discount = $b_discount+$subtotal->sum('discount');
            $b_fape = $b_fape+$subtotal->sum('fape');
            $b_dreservation = $b_dreservation+$subtotal->sum('dreservation');
            $b_deposit = $b_deposit+$subtotal->sum('deposit');
            $b_elearning = $b_elearning+$subtotal->sum('elearning');
            $b_misc = $b_misc+$subtotal->sum('misc');
            $b_book = $b_book+$subtotal->sum('book');
            $b_dept = $b_dept+$subtotal->sum('dept');
            $b_reg = $b_reg+$subtotal->sum('registration');
            $b_tuition = $b_tuition+$subtotal->sum('tuition');
            $b_creservation = $b_creservation+$subtotal->sum('creservation');
            $b_csundry = $b_csundry+$subtotal->sum('csundry');
            ?>
            <tr align='right'>
                <td align='left' colspan="2">Sub Total</td>
                <td>{{number_format($b_cash,2)}}</td>
                <td>{{number_format($b_discount,2)}}</td>
                <td>{{number_format($b_fape,2)}}</td>
                <td>{{number_format($b_dreservation,2)}}</td>
                <td>{{number_format($b_deposit,2)}}</td>
                <td>{{number_format($b_elearning,2)}}</td>
                <td>{{number_format($b_misc,2)}}</td>
                <td>{{number_format($b_book,2)}}</td>
                <td>{{number_format($b_dept,2)}}</td>
                <td>{{number_format($b_reg,2)}}</td>
                <td>{{number_format($b_tuition,2)}}</td>
                <td>{{number_format($b_creservation,2)}}</td>
                <td>{{number_format($b_csundry,2)}}</td>
                <td></td>
                <td></td>
            </tr>
            @if($group == $chunk->last())
            <tr><td colspan='17'><div width="100%"><br><br><br></div></td></tr>
            <tr align='right'>
                <td colspan="2" style='text-align: left'>Forwarding Balance ({{date("M",strtotime($transactiondate))}})</td>
                <td>{{number_format($forwarded->sum('cash'),2)}}</td>
                <td>{{number_format($forwarded->sum('discount'),2)}}</td>
                <td>{{number_format($forwarded->sum('fape'),2)}}</td>
                <td>{{number_format($forwarded->sum('dreservation'),2)}}</td>
                <td>{{number_format($forwarded->sum('deposit'),2)}}</td>
                
                <td>{{number_format($forwarded->sum('elearning'),2)}}</td>
                <td>{{number_format($forwarded->sum('misc'),2)}}</td>
                <td>{{number_format($forwarded->sum('book'),2)}}</td>
                <td>{{number_format($forwarded->sum('dept'),2)}}</td>
                <td>{{number_format($forwarded->sum('registration'),2)}}</td>
                <td>{{number_format($forwarded->sum('tuition'),2)}}</td>
                <td>{{number_format($forwarded->sum('creservation'),2)}}</td>
                <td>{{number_format($forwarded->sum('csundry'),2)}}</td>
                <td></td>
                <td></td>
            </tr>
            <tr align='right'>
                <td colspan="2" style='text-align: left'>Grand Total</td>
                <td>{{number_format($grand_total->sum('cash'),2)}}</td>
                <td>{{number_format($grand_total->sum('discount'),2)}}</td>
                <td>{{number_format($grand_total->sum('fape'),2)}}</td>
                <td>{{number_format($grand_total->sum('dreservation'),2)}}</td>
                <td>{{number_format($grand_total->sum('deposit'),2)}}</td>
                
                <td>{{number_format($grand_total->sum('elearning'),2)}}</td>
                <td>{{number_format($grand_total->sum('misc'),2)}}</td>
                <td>{{number_format($grand_total->sum('book'),2)}}</td>
                <td>{{number_format($grand_total->sum('dept'),2)}}</td>
                <td>{{number_format($grand_total->sum('registration'),2)}}</td>
                <td>{{number_format($grand_total->sum('tuition'),2)}}</td>
                <td>{{number_format($grand_total->sum('creservation'),2)}}</td>
                <td>{{number_format($grand_total->sum('csundry'),2)}}</td>
                <td></td>
                <td></td>
            </tr>
            @endif
        </table>
        @endforeach
    </body>
</html>

