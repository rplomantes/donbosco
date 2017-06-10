@extends('appcashier')
@section('content') 
<style>

</style>
<div class="col-md-4">
    <div class="form form-group">
        <label for ="batch">Select Batch</label>
        <select name="batch" id="batch" class="form form-control">
            <option hidden>--Select--</option>
            @foreach($batches as $level)
                <option value="{{$level->period}}">Batch {{$level->period}}</option>
            @endforeach
        </select>           
     </div>     
</div>
<div class="col-md-4">
    <div class="form form-group">
        <label for ="course">Select Course</label>
        <select name="course" id="course" class="form form-control">
            <option hidden>--Select--</option>
            @foreach($courses as $course)
                <option value="{{$course->course}}">{{$course->course}}</option>
            @endforeach            

        </select>           
     </div>     
</div>
<div class="col-md-4">
    <div class="form form-group">
        <label for ="section">Select Section</label>
        <select name="section" id="section" class="form form-control" onchange="getstudents(this.value)">
            <option hidden>--Select--</option>
        </select>           
     </div>     
</div>
<div class="col-md-12" id="list">
    @if(isset($students))
    <table class="table table-stripped">
        <thead>
        <td>Class No.</td>
        <td >Name</td>
        <td>Total Payment</td>
        <td>Total Training Fee</td>
        <td>Sponsor's Contribution</td>
        <td>TVET Subsidy</td>
        <td>Trainees Contribution</td>
        <td>Remarks</td>
        </thead>
        <tbody>
        @foreach($students as $student)
        <tr>
            <td>{{$student->class_no}}</td>
            <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->extensionname}}</td>
            <td>{{number_format($student->payment, 2, '.', ', ')}}</td>
            <td>{{number_format($student->sponsor+$student->subsidy+$student->amount,2, '.', ', ')}}</td>
            <td>{{number_format($student->sponsor,2, '.', ', ')}}</td>
            <td>{{number_format($student->subsidy,2, '.', ', ')}}</td>
            <td>{{number_format($student->amount,2, '.', ', ')}}</td>
            <td>{{$student->remarks}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <a class="btn btn-primary" href='{{$section}}/edit'>Edit</a>
    @endif
    
    @if(isset($studentledgers))
    <style type='text/css'>
        .no-edit{
         background: none;
         border: none;
         
        }
    </style>    
    <form method="POST" action="{{url('/studentsledger/'.$batch.'/'.$cours.'/'.$section.'/edit')}}">
        {!! csrf_field() !!}
    <table class="table table-stripped">
        <thead>
        <td>Class No.</td>
        <td>Name</td>
        <td>Total Payment</td>
        <td>Total Training Fee</td>
        <td>Sponsor's Contribution</td>

        <td>TVET Subsidy</td>
        <td>Trainees Contribution</td>
        <td>Remarks</td>
        </thead>
        <tbody>
            <?php $count = 1;?>
        @foreach($studentledgers as $student)
        <tr>
            <td>{{$student->class_no}}<input type="hidden" name="idno{{$count}}" value="{{$student->idno}}"></td>
            <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->extensionname}}</td>
            <td><input readonly="readonly" style="width: 100px" type="text" class=" no-edit payment" name="payment{{$count}}" id="payment{{$count}}" value="{{number_format($student->payment, 2, '.', ', ')}}"></td>
            
            <td class="total" style="display:none;">{{$student->sponsor+$student->subsidy+$student->amount}}</td>
            <td>{{number_format($student->sponsor+$student->subsidy+$student->amount, 2, '.', ', ')}}</td>
            
            <td><input  type="text" style="width: 100px" class="sponsors" name="sponsor{{$count}}" id="sponsor{{$count}}" value="{{$student->sponsor}}"></td>
            <td><input type="text" readonly="readonly" style="width: 100px" class="no-edit subsidy" name="subsidy{{$count}}" id="subsidy{{$count}}" value="{{number_format($student->subsidy, 2, '.', ', ')}}" ></td>
            <td><input type="text" style="width: 100px" class="amount" name="trainees{{$count}}" id="trainees{{$count}}" value="{{$student->amount}}"></td>
            <td><input type="text" style="width: 100px" class="desc" name="desc{{$count}}" id="desc{{$count}}" value="{{$student->remarks}}" ></td>
        </tr>
        <?php $count++;?>
        @endforeach
        </tbody>
    </table>
    <button type="submit" class="btn btn-default" style="float: right">Save</button>
    </form>
    @endif    
</div>
<script type="text/javascript">
$('.sponsors').keyup(function(){
    var trainees = $(this).closest("td").siblings().find('.amount').attr('id');
    var subsidy = $(this).closest("td").siblings().find('.subsidy').attr('id');
    var total = $(this).closest("td").siblings('.total').html();
    
    if(trainees == ""){
        trainees = 0;
    }
    if(subsidy == ""){
        subsidy = 0;
    }

    
    var newcontribution = parseInt(total)-(parseFloat($(this).val()) + parseFloat($('#'+subsidy).val()));
    
    $('#'+trainees).val(newcontribution.toFixed(2))
    $(this).val().toFixed(2);
});

$('.amount').keyup(function(){
    var sponsor = $(this).closest("td").siblings().find('.sponsors').attr('id');
    var subsidy = $(this).closest("td").siblings().find('.subsidy').attr('id');
    var total = $(this).closest("td").siblings('.total').html();

    if(sponsor == ""){
        sponsor = 0;
    }
    if(subsidy == ""){
        subsidy = 0;
    }
    
    
    var newcontribution = parseInt(total)-(parseFloat($(this).val()) + parseFloat($("#"+sponsor).val()));
    
    $('#'+subsidy).val(newcontribution.toFixed(2))
    $(this).val().toFixed(2);
});
    
$('#course').change(function(){
   getsection()
});
$('#batch').change(function(){
   getsection()
});


@if(isset($batch))
    $('#batch').val('{{$batch}}')
@endif
@if(isset($cours))
    $('#course').val('{{$cours}}')
    getsection()
    setTimeout(function () {
                     @if(isset($section))
                        $('#section').val('{{$section}}')
                    @endif
    }, 600);    
    
@endif

function getsection(){
    var batch = $('#batch').val();
    var course = $('#course').val();

    $.ajax({
            type: "GET", 
            url: "/gettvetledgersection/" + batch +"/"+ course,
            success:function(data){
                    $('#section').html(data);

            }
    });   

    
}
function loadsec(){
@if(isset($section))
    $('#section').val('{{$section}}')
@endif    
}

function getstudents(section){
    var batch = $('#batch').val();
    var course = $('#course').val();   
    
    document.location = "/studentsledger/" + batch + "/" + course + "/" + section;
    
    
}
</script>
@stop