@extends('app')
@section('content')
<div class='container'>
    <div class='col-md-5'>
        <div class='form-group row' id='cont_batch'>
            <label class='col-form-label col-md-4'>Batch</label>
            <div class='col-md-8'>
                <select class='form-control get_sec' name='batch' id='batch'>
                    <option value='' hidden='hidden'></option>
                    @foreach($batches as $batch)
                    <option value='{{$batch->period}}'>Batch {{$batch->period}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class='form-group row' id='cont_course'>
            <label class='col-form-label col-md-4'>Course</label>
            <div class='col-md-8'>
                <select class='form-control get_sec' name='course' id='course'>
                    <option value='' hidden='hidden'></option>
                    @foreach($courses as $course)
                    <option value='{{$course->course_id}}'>{{$course->course}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class='form-group row' id='cont_semester'>
            <label class='col-form-label col-md-4'>Semester</label>
            <div class='col-md-8'>
                <select class='form-control' name='semester' id='semester'>
                    <option value='1'>1st Semester</option>
                    <option value='2'>2nd Semester</option>
                </select>
            </div>
        </div>
        <div class='form-group row' id='cont_section'>
            <label class='col-form-label col-md-4'>Section</label>
            <div class='col-md-8'>
                <select class='form-control' name='section' id='section'>
                </select>
            </div>
        </div>
        <button id="print_all" style="display:none" class="btn btn-danger col-md-12">Print</button>
    </div>
    <div id='studentlist' class='col-md-7'></div>
</div>
<script>
    $('.get_sec').change(function(){
       arrays = {};
       arrays['batch'] = $('#batch').val();
       arrays['course'] = $('#course').val();
       
       $.ajax({
           type:"GET",
           url:"{{route('option_tvetsection')}}",
           data:arrays,
           success:function(data){
               $('#section').html(data);
               $('#print_all').css("display","none");
               sessionStorage.setItem("option_tvetsection", data);
           },
           error:function(){
               alert("An error occurs. Please call administrator");
           }
           
       });
    });
    
    $('#section,#semester').change(function(){
       arrays = {};
       arrays['section'] = $('#section').val();
       arrays['semester'] = $('#semester').val();
       
       $.ajax({
           type:"GET",
           url:"{{route('classList_tvetsection')}}",
           data:arrays,
           success:function(data){
               $('#studentlist').html(data);
               sessionStorage.setItem("classList_tvetsection", data);
               sessionStorage.setItem("option_tvetsection", $('#section').html());
           },
           error:function(){
               alert("An error occurs. Please call administrator");
           }
           
       });
    });
    
    $('#section').change(function(){
        $('#print_all').removeAttr("style");

        $('#section option:selected', this).remove();
        $('#section option:selected').attr('selected','selected');
    });
    
    if (performance.navigation.type == 1) {
        sessionStorage.removeItem('classList_tvetsection');
        sessionStorage.removeItem('option_tvetsection');
    } 
    
    if(sessionStorage.getItem("option_tvetsection") !== null){
        $('#section').html(sessionStorage.getItem("option_tvetsection"));
    }
    
    if(sessionStorage.getItem("classList_tvetsection") !== null){
        $('#studentlist').html(sessionStorage.getItem("classList_tvetsection"));
    }
</script>
@stop