<?php
use App\Http\Controllers\Accounting\Helper as AcctHelper;
?>
<form method='POST' target='_blank' id="accountForm">
    {!!csrf_field()!!}
    <div class='col-md-12' style='font-size:13pt'>
        Show:
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='date' name='date'>Tran Date</label>
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='refno' name='refno'>Ref. No.</label>
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='name' name='name'>Name</label>
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='level' name='level'>Level</label>
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='section' name='section'>Section</label>
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='debit' name='debit'>Debit</label>
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='credit' name='credit'>Credit</label>
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='entry' name='entry'>Entry</label>
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='department' name='department'>Department</label>
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='office' name='office'>Office</label>
        <label class="checkbox-inline"><input class='col' type="checkbox" value="1" id='remarks' name='remarks'>Remarks</label>
    </div>
    <br>
    <hr>

    <table class="table table-striped">
        <tr>
            <th class='date' width="8%">Tran. Date</th>
            <th class='refno' width="10%">Reference No</th>
            <th class='name' width="20%">Name</th>
            <th class='level'>Level</th>
            <th class='section'>Section</th>
            <th class='debit'>Debit</th>
            <th class='credit'>Credit</th>
            <th class='entry'>Entry</th>
            <th class='department'>Department</th>
            <th class='office'>Office</th>
            <th class='remarks'>Particular</th>
        </tr>
        @foreach($viewaccounts as $viewaccount)
        <tr>
            <td class='date'>{{$viewaccount->transactiondate}}</td>
            <td class='refno'>{{$viewaccount->receiptno}}</td>
            <td class='name'>{{$viewaccount->payee}}</td>
            <td class='level'>{{$viewaccount->level}}</td>
            <td class='section'>{{$viewaccount->section}}</td>
            <td class='debit' align="right">{{number_format($viewaccount->debit,2)}}</td>
            <td class='credit' align="right">{{number_format($viewaccount->credit,2)}}</td>
            <td class='entry'>{{AcctHelper::get_entryType($viewaccount->entry_type)}}</td>
            <td class='department'>{{$viewaccount->acctdepartment}}</td>
            <td class='office'>{{$viewaccount->subdepartment}}</td>
            <td class='remarks'>{{$viewaccount->particular}}</td>
        </tr>
        @endforeach
        <tr>
            <td class='date'></td>
            <td class='refno'></td>
            <td class='name'></td>
            <td class='level'></td>
            <td class='sectino'></td>
            <td class='debit' align="right">{{number_format($viewaccounts->sum('debit'),2)}}</td>
            <td class='credit' align="right">{{number_format($viewaccounts->sum('credit'),2)}}</td>
            <td class='entry'></td>
            <td class='department'></td>
            <td class='office'></td>
            <td class='remarks'></td>
        </tr>
    </table>
    <button class="col-md-6 btn btn-danger" onclick="printaccount()">Print</button>
    <button class="col-md-6 btn btn-success" onclick="downloadaccount()">Download</button>
</form>
<script>
    $(".col").prop('checked','checked');
    $(".col").change(function(){
        var column = $(this).attr('id');
        if($(this).is(':checked')){
            $('.'+column).css('display','table-cell');
        }else{
            $('.'+column).css('display','none');
        }
    });
    
    function printaccount(){
        forms = document.getElementById('accountForm');
        forms.action = '{{route("printiassummary")}}';
        
        forms.submit();
    }
    
    function downloadaccount(){
        forms = document.getElementById('accountForm');
        forms.action = '{{route("dliassummary")}}';
        
        forms.submit();
    }
</script>
    
