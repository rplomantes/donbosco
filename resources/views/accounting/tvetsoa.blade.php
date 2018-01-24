@extends('appcashier')
@section('content')
<div class="container-fluid">
    <form method="POST" action="{{url('/gettvetsoasummary')}}">
        <div class="col-md-6">
            {!! csrf_field() !!}
            <div id="duedate-container" class="col-md-12">
                <h5>Due Date</h5>
                <div class="form form-group col-md-4">
                    <label>Month</label>
                    <select id="month" name="month" class="form form-control">
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="form form-group col-md-4">
                    <label>Day</label>
                    <select id="day" name="day" class="form form-control">
                        <?php
                        for($i=1;$i<=31;$i++){
                          echo "<option value = '$i'>$i</option>";
                        }
                        ?>
                    </select>    
                </div>  
                <div class="form form-group col-md-4">
                    <label>year</label>
                    <select id="year" name="year" class="form form-control">
                        <?php 
                        foreach($sys as $sy){
                            $schoolyears = $sy->schoolyear;
                        }
                        $curr_year = date("Y");
                        while($curr_year > $schoolyears-1){
                            ?>
                        <option value="{{$curr_year}}">{{$curr_year}}</option>
                        <?php
                        $curr_year = $curr_year-1;
                        };
                        ?>
                    </select>    
                </div>
            </div>
            <div id="batch-container" class="col-md-12">
                <h5>Batch</h5>
                <select class="form-control" id="batch" name="batch" onchange="getcourse()">
                    <option value="" hidden="hidden">-- Select Batch --</option>
                    @foreach($batch as $batch)
                    <option value="{{$batch->period}}">{{$batch->period}}</option>
                    @endforeach
                </select>
            </div>
            <div id="course-container" class="col-md-6">
            </div>
            <div id="section-container" class="col-md-6">
            </div>
            <div class="form form-group col-md-12">
                <hr>
                <label>Custom reminder</label>
                <textarea row="4" id="reminder" name="reminder" class="form form-control"></textarea>
             </div> 
            <div class="col-md-3 col-md-offset-9">
                <input type="submit" class="form-control btn btn-danger" value="OK">
            </div>
        </div>
    </form>
</div>
<script>
    $('#duedate').datepicker();
    function getcourse(){
        $("#course-container").load('/getcourse/'+$('#batch').val()+'/getsection');
    }
    
    function getsection(){
        var arrays ={} ;
        arrays['batch'] = $('#batch').val();
        arrays['course']= $('#course').val();
        
        $.ajax({
                   type: "GET", 
                   url: "/gettvetsection", 
                   data: arrays,
                   success:function(data){
                       $("#section-container").html(data);
                   }
       }); 
    }
</script>
@stop
