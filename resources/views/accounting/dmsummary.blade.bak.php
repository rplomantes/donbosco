@extends('appaccounting')
@section('content')

<div class="container">
    <h3>Debit Memo Journal</h3>
    <div class="col-md-3">
        <div class="form form-group">
            <label>From</label>
            <input type="text" id="fromtran" class="form-control" value="{{$fromtran}}">
        </div>
    </div> 
    <div class="col-md-3">
        <div class="form form-group">    
            <label>To</label>
             <input type="text" id="totran" class="form-control" value="{{$totran}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form form-group">
            <br>
            <button onclick="showtran()" class="btn btn-primary form-control">View Transaction</button>
        </div>
    </div>    
    <hr />
    <div class="form form-group">
        @foreach($dms as $dm)
        <?php
        $accounts = DB::Select("select r.accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa join "
                . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where entry_type=2 and isreverse = '0' and refno='$dm->refno' group by accountingcode "
                . "UNION ALL "
                . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where entry_type=2 and isreverse = '0' and refno='$dm->refno' group by accountingcode) r "
                . "on coa.acctcode = r.accountingcode group by accountingcode order by coa.id");
        ?>
        <b>Reference No:</b>{{$dm->refno}}
        <table class="table table-striped table-bordered">
            <?php 
            $totaldebit = 0;
            $totalcredit = 0;
            ?>
            <thead><tr><th>Acct No.</th><th>Account Title</th><th>Office</th><th>Debit</th><th>Credit</th></tr></thead>
            @foreach($accounts as $account)
            <?php 
            $totaldebit = $totaldebit + $account->debit;
            $totalcredit = $totalcredit + $account->credits;
            ?>        
            <tr><td>{{$account->accountingcode}}</td><td>{{$account->accountname}}</td><td></td><td style="text-align: right">
                    @if($account->debit > 0)
                    {{number_format($account->debit, 2, '.', ', ')}}
                    @endif
                </td><td style="text-align: right">
                    @if($account->credits > 0)
                    {{number_format($account->credits, 2, '.', ', ')}}
                    @endif
                </td></tr>
            @endforeach
            <tr><td colspan="2" style="text-align: right"><b>Total</b></td><td style="text-align: right">{{number_format($totaldebit, 2, '.', ', ')}}</td><td style="text-align: right">{{number_format($totalcredit, 2, '.', ', ')}}</td></tr>
        </table>
        
        @endforeach
    </div>
    
<div class="col-md-12"><a class="form-control btn btn-danger" href="{{url('printdmjournal',array($fromtran,$totran))}}">Print</a></div>
</div>
    

<script>
function showtran(){
    var fromtran = document.getElementById('fromtran').value
    var totran = document.getElementById('totran').value
    document.location="/dmsummary/" + fromtran + "/" + totran
}
</script>
@stop

