@extends('appaccounting')
@section('content')

<div class='container_fluid'>
    <div class=" col-md-12 form form-group" >
       <label class="label label-danger" style="font-size:15pt; background-color: pink;"> Ref No : {{$refno}}</label>
    </div> 
    <div class="col-md-12">
        <table class="table table-bordered"><thead><tr><th>Acct Code</th><th>Account Name</th><th>Subsidiary</th><th>Department</th><th>Debit</th><th>Credit</th></tr>
            </thead><tbody id="partialentry">
                  <?php
                  $accountings = \App\Accounting::where('refno',$refno)->get();
                  ?> 
                @foreach($accountings as $accounting)
                    <tr><td>{{$accounting->accountcode}}</td><td>{{$accounting->accountname}}</td>
                        <td>{{$accounting->subsidiary}}</td><td>{{$accounting->sub_department}}</td>
                        <td>{{$accounting->debit}}</td><td>{{$accounting->credit}}</td></tr>
                @endforeach
            </tbody>
        </table>    
    </div>
    <div class="col-md-12">
        <div class="col-md-6"></div>
        <div class="col-md-3"><a href="{{url('/listofentry')}}" class="form form-control btn btn-primary">Back To List</a></div>
        <div class="col-md-3"><a href="{{url('/editjournalentry',$refno)}}" class="form form-control btn btn-danger">Modify Entry</a></div>
    </div>    
</div>    

@stop

