@extends('app')
@section('content')
<div class='conatainer'>
    <h4>Set schedule for entrance exam</h4>
    <div class='col-md-6 col-md-offset-3'>
        @foreach ($errors->all() as $error)
        <div class='alert alert-danger'>
                {{ $error }}
        </div>
        @endforeach
        <form class="form-horizontal" method="POST" action='{{url("/assignStudent")}}' >
            {!!csrf_field()!!}
            <input type="hidden" value="{{$idno}}" name="idno">
            <div class="form-group">
                <label class="control-label col-md-2">Level</label>
                <div class="col-md-4">
                    <select class="form-control" name="level" onchange="getschedule(this.value)">
                        <option value="">Select Level</option>
                        @foreach($levels as $level)
                        <option value="{{$level->level}}">{{$level->level}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="schedule"></div>
            <button class='btn btn-success' type='submit'>Submit</button>
            <a class='btn btn-danger' href='/'>Skip >></a>
        </form>
        
    </div>
</div>
<script type="text/javascript">
    function getschedule(level){
        
        $.ajax({
          type:"GET",
          url:"/getschedule/"+level,
          success:function(data){
              $("#schedule").html(data);
          }
        });
    }
</script>
@stop