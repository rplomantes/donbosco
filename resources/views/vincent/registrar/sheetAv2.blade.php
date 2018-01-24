@extends("app")
@section("content")

<div class="container">
    <div class="col-md-4">
        <div id="levelfield">
            <div class="form-group">
                <label>Select Level</label>
                <select class="form-control" id="level" onchange="levelchange()">
                    <option selected="selected" hidden="hidden">-- Select --</option>
                    @foreach($levels as $level)
                    <option value="{{$level->level}}">{{$level->level}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="field2">
            
        </div>
    </div>
    <div class="col-md-4">
        
    </div>
    <div class="col-md-4">
        
    </div>
</div>
<script>
    var strand = "";
    function levelchange(){
        var level = $("#level").val();
        if(level != "Grade 11" || level != "Grade 12"){
            var arrays ={} ;
            arrays['level'] = level;
                $.ajax({
                    type: "GET", 
                    url: "/getstrand",
                    data : arrays,
                    success:function(data){

                        }
                    });             
        }
    }
</script>
<div style="display:none;">
    <div class="form-group" id="section">
        <label>Select Level</label>
        <select class="form-control" id="level" onchange="gradechange()">

        </select>
    </div>
</div>
@stop

