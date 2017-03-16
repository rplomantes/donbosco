@extends('appcashier')
@section('content')
<style>
    .clickable-row{
        cursor:pointer;
    }
</style>
<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <?php $student = App\User::where('idno',$idno)->first(); ?>
        <h4 class="col-md-2">{{$idno}}</h4>
        <h4 class="col-md-10"><b>{{$student->firstname}} {{$student->middlename}} {{$student->lastname}}</b></h4>
        
        <br><br>
        <h4>Books</h4>
        <form role="form" method="POST" action="{{ url('/books/update') }}">
            {!!csrf_field()!!}
            <table class="tbl table">
                @foreach($books as $book)
                    @if($book->status == 0)
                    <tr style="background-color: #ececec;color:#989494;">
                        <td ></td>
                        <td>{{$book->description}}</td>
                        <td>Unpaid</td>
                    </tr>
                    @elseif($book->status == 1)
                    <tr class="clickable-row">
                        <td><input type="checkbox" name="book[{{$book->id}}]" id="{{$book->id}}"></td>
                        <td>{{$book->description}}</td>
                        <td>Ok</td>
                    </tr>
                    @else
                    <tr style="background-color: #ececec;color:#989494;">
                        <td></td>
                        <td>{{$book->description}}</td>
                        <td>Claimed</td>
                    </tr>
                    @endif
                @endforeach
            </table>
            <div class="col-md-offset-11 col-md-1"><input type="submit" value="Save" class="btn btn-danger"></div>
        </form>
    </div>
</div>
    <script>

$(document).ready(function($) {
    $(".clickable-row").click(function() {
        
        if($(this).find('input:checkbox').is(':checked')){
            $(this).find('input:checkbox').prop('checked', false);
        }else{
            $(this).find('input:checkbox').prop('checked', true);
        }
    });
});
    </script>
@stop