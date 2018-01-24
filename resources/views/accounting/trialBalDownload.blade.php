<?php 
use App\Http\Controllers\Accounting\Helper as AcctHelper;
?>
        <table class="table table-striped ">
            <?php 
            $totaldebit = 0;
            $totalcredit = 0;
            ?>
            <thead>
                <tr>
                    <td colspan='4' style='font-size:12px'>Trial Balance</td>
                </tr>
                <tr>
                    <td colspan='4' style='font-size:12px'>From {{$fromtran}} to {{$totran}}</td>
                </tr>
                <tr>
                    <th>Acct No.</th>
                    <th>Account Title</th>
                    <th>Debit</th>
                    <th>Credit</th>
                </tr>
            </thead>
            @foreach($trials as $trial)
 
            <tr><td>{{$trial->accountingcode}}</td><td>{{$trial->accountname}}</td><td style="text-align: right">
                    @if($trial->entry == 'debit')
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2)<0)
                        (
                        @endif
                    {{number_format(abs(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry)),2)}}
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2)<0)
                        )
                        @endif
                    <?php
                    $totaldebit = $totaldebit + round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2);
                    ?>
                    @else
                        {{number_format(0,2)}}
                    @endif

                </td><td style="text-align: right">
                    @if($trial->entry == 'credit')
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2)<0)
                        (
                        @endif
                    {{number_format(abs(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry)),2)}}
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2)<0)
                        )
                        @endif
                        <?php
                        $totalcredit = $totalcredit + round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2);
                        ?>
                    @else
                        {{number_format(0,2)}}
                    @endif
                </td>
            </tr>
            @endforeach
            <tr><td colspan="2" style="text-align: right"><b>Total</b></td><td style="text-align: right">{{number_format($totaldebit, 2, '.', ', ')}}</td><td style="text-align: right">{{number_format($totalcredit, 2, '.', ', ')}}</td></tr>
        </table>