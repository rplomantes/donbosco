@extends('app')
@section('content')
<style type="text/css" media="print">
    .container-fluid{
        padding-left:0px;
        padding-right:0px;
    }
    td{border:1px solid;
        text-align: center;}
</style>

<div class="container-fluid">
    <div style="margin-bottom: 20px" class="no-print">
    <button style='padding-top: 10px;padding-bottom: 10px;' class="btn btn-default" data-toggle="collapse" data-target="#menu" onclick="expand()"><strong>
					<span style="display: block;width: 22px;height: 2px;border-radius: 1px;background-color: gray;" class="icon-bar"></span>
					<span style="margin-top: 4px;display: block;width: 22px;height: 2px;border-radius: 1px;background-color: gray;" class="icon-bar"></span>
					<span style="margin-top: 4px;display: block;width: 22px;height: 2px;border-radius: 1px;background-color: gray;" class="icon-bar"></span>        
        </strong></button>
        
        <span id="semester" style="margin-left:20px;visibility: hidden"><a class="btn btn-default sem btn-primary" onclick="changesem(1)" style="border-bottom-right-radius: 0px;border-top-right-radius: 0px;">1st Sem</a><a class="btn btn-default sem" style="border-bottom-left-radius: 0px;border-top-left-radius: 0px;" onclick="changesem(2)">2nd Sem</a></span>
    </div>
    <div class="col-md-3 collapse in" id='menu'>
        <?php $menu = 0;?>
        @foreach($levels as $level)
        <?php $sections = \App\CtrSection::where('level',$level->level)->orderBy('strand','ASC')->get();
            
            $strand = null;
            $menu++;
            ?>
        <button style='display: block;width:100%;border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;' class="no-print btn btn-default" data-toggle="collapse" data-target="#menu{{$menu}}"><strong>{{$level->level}}</strong></button>
        <div class="no-print collapse" id="menu{{$menu}}" width='100%' style='border:1px solid #cccccc;border-top:none'>
            
            @foreach($sections as $section)
                @if($section->strand != '')
                <div width='100%'>
                    @if($section->strand != $strand)
                        <?php $strand = $section->strand; ?>
                        <div style='border-bottom: 1px solid #cccccc;border-top: 1px solid #cccccc'>{{$section->strand}} </div>
                            <!--ul-->
                                @foreach($sections as $strandsec)
                                    @if($strandsec->strand == $strand)
                                        <a class="section" onclick="seefinal('{{$strandsec->section}}','{{$level->level}}','{{$level->department}}','{{$section->strand}}')"><div style='border-bottom: 1px solid #cccccc;padding-left: 30px'>{{$strandsec->section}}</div></a>
                                    @endif
                                @endforeach                                
                            <!--/ul-->
                        
                    @endif
                </div>
                @else
                <a class="section" onclick="seefinal('{{$section->section}}','{{$level->level}}','{{$level->department}}')"><div style='border-bottom: 1px solid #cccccc'>{{$section->section}}</div></a>
                @endif
            @endforeach
        </div>            
        @endforeach
        <br>
        
        <button style='display: block;width:100%;border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;' class="btn btn-default" data-toggle="collapse" data-target="#tvet"><strong>TVET</strong></button>
        <div class="collapse" id="tvet" width='100%' style='border:1px solid #cccccc;border-top:none'>
          @foreach($tvet as $course)
          <a class="section" onclick="seeGradeTvet('{{$course->courses}}')"><div style='border-bottom: 1px solid #cccccc'>{{$course->courses}}</div></a>
          @endforeach
        </div>
        
  </div>
    <div class="col-md-9" id="display" style="overflow-x:scroll;">
        
    </div>
</div>
<script type="text/javascript">
    var sections
    var levels
    var strands
    var departments
    var semester = 1
    
    $("#semester").on("click", "a.sem", function(){
        $(this).siblings().removeClass('btn-primary');
        $(this).addClass('btn-primary');
        $(this).blur();
    });
    
    function expand(){
        
        var displays = document.getElementById('menu');
        
        if(!hasClass(displays,'in')){
            $( "#display" ).removeClass('col-md-12');
            $( "#display" ).addClass('col-md-9');
        }else{
            $("#display").removeClass('col-md-9');
            $("#display").addClass('col-md-12');
        }
    }
    
    function hasClass(element, cls) {
        return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
    }
    
    function seefinal(section,level,department,strand){
        var loading = document.getElementById ( "semester" ) ;
        if(department == 'Senior High School'){
            
            loading.style.visibility = "visible"
            sections = section
            levels = level
            strands = strand
            departments = department
            seeseniorfinal();
        }
        else{
            loading.style.visibility = "hidden"
            arrays ={} ;
            arrays['section'] = section;
            arrays['level']= level;
            arrays['strand']= strand;
            arrays['department']= department;
            
            $.ajax({
                   type: "GET", 
                   url: "/showfinale",
                   data : arrays,
                   success:function(data){
                       $("#display").html(data);
                       }
                   });            
        }

    }
    
    function seeseniorfinal(){
               
        arrays ={} ;
        arrays['section'] = sections;
        arrays['level']= levels;
        arrays['strand']= strands;
        arrays['department']= departments;
        arrays['sem']= semester;
        $.ajax({
               type: "GET", 
               url: "/showfinale",
               data : arrays,
               success:function(data){
                   $("#display").html(data);
                   }
               });
               
    }
    
    function changesem(sem){
        semester = sem;
        
        seeseniorfinal();
    }

</script>
@endsection