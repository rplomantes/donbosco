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
                    @if($book->amount-($book->payment + $book->debitmemo) != 0 && $book->status != 2)
                    <tr style="background-color: #ececec;color:#989494;">
                        <td ></td>
                        <td>{{$book->description}}</td>
                        <td></td>
                        <td>Unpaid</td>
                    </tr>
                    @elseif($book->amount-($book->payment + $book->debitmemo) == 0 && $book->status != 2)
                    <tr class="clickable-row">
                        <td><input type="checkbox" name="book[{{$book->id}}]" id="{{$book->id}}"></td>
                        <td>{{$book->description}}</td>
                        <td></td>
                        <td>Ok</td>
                    </tr>
                    @else
                    <tr style="background-color: #ececec;color:#989494;">
                        <td></td>
                        <td>{{$book->description}}</td>
                        <td><a href="#" onclick="unclaim({{$book->id}})">Unclaim</a></td>
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

function unclaim(id){
             $.ajax({
            type: "GET", 
            url: "/unclaim/" +  id, 
            success:function(data){
                location.reload();
                }
            });
}
    </script>
@stop