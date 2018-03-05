@extends('appaccounting')
@section('content')
<div class="container-fluid">
    <div class="col-md-3">
        @include('accounting.EnrollmentAssessment.leftmenu')
    </div>
    <div class="col-md-9">
        <h4>{{$module_info['title']}} - {{$level}}</h4>
        <hr>
        <div class="col-md-8">
            <table class="table table-bordered">
                <tr>
                    <td>Book</td>
                    <td width="30%">Price</td>
                </tr>
                @foreach($books as $book)
                <tr>
                    <td><input class="form-control" value="{{$book->subsidiary}}"></td>
                    <td><input style='text-align: right' class="form-control divide" value="{{$book->amount}}"></td>
                </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <td><h4>Total</h4></td>
                        <td><input style='text-align: right' class="form-control divide" value="{{$books->sum('amount')}}"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
</div>
@stop