@extends("appcashier")
@section("content")
<div class="container">
    <div class="col-md-offset-2 col-md-8">
        <input type="text" class="form-control col-md-12" placeholder="Search" id="search">
        <div id="students">
            <table class="table table-stripped">
                <tr>
                    <td>Name</td>
                    <td></td>
                </tr>
                @foreach($students as $student)
                <tr>
                    <td width="80%">
                        {{$student->fullname}}
                    </td>
                    <td>
                        <a href="{{url('viewnonstudent',$student->idno)}}">view</a>
                    </td>   
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
<script>
    $("#search").keyup(function(){
        arrays ={};
        arrays['name'] = $("#search").val();
         $.ajax({
        type: "GET", 
        url: "/searchnonstudent",
        data:arrays,
        success:function(data){
            $('#students').html(data);  
            }
        });
    });
</script>
@stop