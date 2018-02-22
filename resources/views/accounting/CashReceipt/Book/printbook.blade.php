<?php
ini_set('memory_limit', '256M');
$forwarded = $records->where('refno','forwarded',false);

$grand_total = $records->where('isreverse',0,false);
                    
$entrysundies = \App\RptCashReceiptBookSundries::with('RptCashreceiptBook')->where('idno',\Auth::user()->idno)->get();

$totalsundries = $entrysundies->filter(function($item){
    if($item->RptCashreceiptBook->isreverse == 0){
        return true;
    }
});

$b_cash = 0;
$b_discount = 0;
$b_dsundry = 0;
$b_elearning = 0;
$b_misc = 0;
$b_book = 0;
$b_dept = 0;
$b_reg = 0;
$b_tuition = 0;
$b_creservation = 0;
$b_csundry = 0;

?>
<html>
    <head>
        <style>
            .payee{
                text-align: left
            }
            td,th{
                font-size: 9pt;
            }
            .report{
                page-break-after: always
            }
        </style>
    </head>
    <body>
@include('inludes.header')
@include('inludes.footer')
        @foreach($chunk as $group)
        
        <table 
            @if($group != $chunk->last())
            class="report"
            @endif
            border="1" cellspacing="0" cellpadding="2" width="100%">
            <tr>
                <th rowspan="2">Receipt No.</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Debit<br>Cash / Check</th>
                <th rowspan="2">Debit<br>Discount</th>

                <th rowspan="2">E - Learning</th>
                <th rowspan="2">Misc</th>
                <th rowspan="2" rowspan="2">Book</th>
                <th rowspan="2">Department<br> Facilities</th>
                <th rowspan="2">Registration Fee</th>
                <th rowspan="2">Tuition Fee</th>
                <th rowspan="2">Credit<br>Reservation</th>
                <th colspan="3" align="center">SUNDRIES</th>
                <th rowspan="2">Status</th>
            </tr>
            <tr>
                <th>Debit<br>Sundry</th>
                <th>Credit<br>Sundry</th>
                <th>Account</th>
            </tr>
            </tr>
            
            @if($group == $chunk->first())
            <tr align='right'>
                <td colspan="2" style='text-align: left'>Forwarding Balance ({{date("M",strtotime($transactiondate))}})</td>
                <td>{{number_format($forwarded->sum('cash'),2)}}</td>
                <td>{{number_format($forwarded->sum('discount'),2)}}</td>
                
                <td>{{number_format($forwarded->sum('elearning'),2)}}</td>
                <td>{{number_format($forwarded->sum('misc'),2)}}</td>
                <td>{{number_format($forwarded->sum('book'),2)}}</td>
                <td>{{number_format($forwarded->sum('dept'),2)}}</td>
                <td>{{number_format($forwarded->sum('registration'),2)}}</td>
                <td>{{number_format($forwarded->sum('tuition'),2)}}</td>
                <td>{{number_format($forwarded->sum('creservation'),2)}}</td>
                
                <td>{{number_format($forwarded->sum('dsundry'),2)}}</td>
                <td>{{number_format($forwarded->sum('csundry'),2)}}</td>
                <td colspan="2"></td>
            </tr>
            <tr><td colspan='15'></td></tr>
            @else
            <tr align='right'>
                <td colspan="2" style='text-align: left'>Balance Brought Forward</td>
                <td>{{number_format($b_cash,2)}}</td>
                <td>{{number_format($b_discount,2)}}</td>
                
                <td>{{number_format($b_elearning,2)}}</td>
                <td>{{number_format($b_misc,2)}}</td>
                <td>{{number_format($b_book,2)}}</td>
                <td>{{number_format($b_dept,2)}}</td>
                <td>{{number_format($b_reg,2)}}</td>
                <td>{{number_format($b_tuition,2)}}</td>
                <td>{{number_format($b_creservation,2)}}</td>
                
                <td>{{number_format($b_dsundry,2)}}</td>
                <td>{{number_format($b_csundry,2)}}</td>
                <td colspan="2"></td>
            </tr>
            @endif
            
            @foreach($group as $receipt)
            <?php $receiptsundry = $entrysundies->where('refno',$receipt->refno,false);?>
            <tr style='text-align: right'>
                <td rowspan='{{$receipt->row_count}}' style='text-align: left'>{{$receipt->receiptno}}</td>
                <td  width='15%' rowspan='{{$receipt->row_count}}' style='text-align: left'>
                    <div class="payee">
                        @if(strlen($receipt->from)>25 && $receipt->row_count ==1)
                        {{substr($receipt->from, 0 , 20)}}...
                        @else 
                        {{$receipt->from}}
                        @endif
                        
                    </div>
                </td>
                
                <td rowspan='{{$receipt->row_count}}'>{{number_format($receipt->cash,2)}}</td>
                <td rowspan='{{$receipt->row_count}}'>{{number_format($receipt->discount,2)}}</td>
                
                <td rowspan='{{$receipt->row_count}}'>{{number_format($receipt->elearning,2)}}</td>
                <td rowspan='{{$receipt->row_count}}'>{{number_format($receipt->misc,2)}}</td>
                <td rowspan='{{$receipt->row_count}}'>{{number_format($receipt->book,2)}}</td>
                <td rowspan='{{$receipt->row_count}}'>{{number_format($receipt->dept,2)}}</td>
                <td rowspan='{{$receipt->row_count}}'>{{number_format($receipt->registration,2)}}</td>
                <td rowspan='{{$receipt->row_count}}'>{{number_format($receipt->tuition,2)}}</td>
                <td rowspan='{{$receipt->row_count}}'>{{number_format($receipt->creservation,2)}}</td>
                <td>@if(count($receiptsundry)>0 && $receiptsundry->first()->debit >0){{number_format($receiptsundry->first()->debit,2)}}@endif</td>
                <td>@if(count($receiptsundry)>0 && $receiptsundry->first()->credit >0){{number_format($receiptsundry->first()->credit,2)}}@endif</td>
                <td style="white-space: nowrap;" align='left'>@if(count($receiptsundry)>0){{$receiptsundry->first()->particular}}@endif</td>
                <td rowspan='{{$receipt->row_count}}' align='center'>
                    @if($receipt->isreverse == 0)
                    OK
                    @else
                    Cancelled
                    @endif
                </td>
            </tr>
            
               @if(count($receiptsundry)>1)
                    @foreach($receiptsundry as $sundryRec)
                        @if($sundryRec != $receiptsundry->first())
                        <tr>

                            <td align='right'>@if($sundryRec->debit > 0) {{number_format($sundryRec->debit,2)}}@endif</td>
                            <td align='right'>@if($sundryRec->credit > 0){{number_format($sundryRec->credit,2)}}@endif</td>
                            <td align='left' style="white-space: nowrap;">{{$sundryRec->particular}}</td>

                        </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
            
            <?php
            $subtotal = $group->where('isreverse',0,false);
            $b_cash = $b_cash+$subtotal->sum('cash');
            $b_discount = $b_discount+$subtotal->sum('discount');
            $b_dsundry = $b_dsundry+$subtotal->sum('dsundry');

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
                
                <td>{{number_format($b_elearning,2)}}</td>
                <td>{{number_format($b_misc,2)}}</td>
                <td>{{number_format($b_book,2)}}</td>
                <td>{{number_format($b_dept,2)}}</td>
                <td>{{number_format($b_reg,2)}}</td>
                <td>{{number_format($b_tuition,2)}}</td>
                <td>{{number_format($b_creservation,2)}}</td>
                
                <td>{{number_format($b_dsundry,2)}}</td>
                <td>{{number_format($b_csundry,2)}}</td>
                <td colspan="2"></td>
            </tr>
        </table>
        @endforeach
        
        <div width="100%"><br></div>
        <table border="1" cellspacing="0" cellpadding="2" width="100%" style="page-break-inside:never">
            <tr>
                <th>Receipt No.</th>
                <th>Name</th>
                <th>Debit<br>Cash / Check</th>
                <th>Debit<br>Discount</th>
                <th>Debit<br>Sundry</th>
                
                <th>E - Learning</th>
                <th>Misc</th>
                <th>Book</th>
                <th>Department<br> Facilities</th>
                <th>Registration Fee</th>
                <th>Tuition Fee</th>
                <th>Credit<br>Reservation</th>
                <th>Credit<br>Sundry</th>
                <th>Status</th>
            </tr>
            <tr align='right'>
                <td colspan="2" style='text-align: left'>Forwarding Balance ({{date("M",strtotime($transactiondate))}})</td>
                <td>{{number_format($forwarded->sum('cash'),2)}}</td>
                <td>{{number_format($forwarded->sum('discount'),2)}}</td>
                
                <td>{{number_format($forwarded->sum('elearning'),2)}}</td>
                <td>{{number_format($forwarded->sum('misc'),2)}}</td>
                <td>{{number_format($forwarded->sum('book'),2)}}</td>
                <td>{{number_format($forwarded->sum('dept'),2)}}</td>
                <td>{{number_format($forwarded->sum('registration'),2)}}</td>
                <td>{{number_format($forwarded->sum('tuition'),2)}}</td>
                <td>{{number_format($forwarded->sum('creservation'),2)}}</td>
                <td>{{number_format($forwarded->sum('dsundry'),2)}}</td>
                <td>{{number_format($forwarded->sum('csundry'),2)}}</td>
                <td></td>
            </tr>
            <tr align='right'>
                <td colspan="2" style='text-align: left'>Grand Total</td>
                <td>{{number_format($grand_total->sum('cash'),2)}}</td>
                <td>{{number_format($grand_total->sum('discount'),2)}}</td>
                
                <td>{{number_format($grand_total->sum('elearning'),2)}}</td>
                <td>{{number_format($grand_total->sum('misc'),2)}}</td>
                <td>{{number_format($grand_total->sum('book'),2)}}</td>
                <td>{{number_format($grand_total->sum('dept'),2)}}</td>
                <td>{{number_format($grand_total->sum('registration'),2)}}</td>
                <td>{{number_format($grand_total->sum('tuition'),2)}}</td>
                <td>{{number_format($grand_total->sum('creservation'),2)}}</td>
                
                <td>{{number_format($grand_total->sum('dsundry'),2)}}</td>
                <td>{{number_format($grand_total->sum('csundry'),2)}}</td>
                <td></td>
            </tr>
        </table>
        
    </body>
</html>

