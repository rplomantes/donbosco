<style>
    .description{
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        width:100%;
    }
</style>
<h4><b>Other Account</b></h4>
<table class="table table-striped">
    <tr>
        <td style="max-width:40px">Description</td>
        <td>Amount</td>
    </tr>
    @foreach($accounts as $account)
    <tr>
        <td title="{{$account->description}}"><div  class="description"  style="max-width:300px">{{$account->description}}</div></td>
        <td align="right">{{number_format($account->amount - ($account->payment - $account->debitmemo - $account->otherdiscount),2)}}</td>
    </tr>
    @endforeach
</table>