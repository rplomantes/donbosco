@extends('app')
@section('content')
<style type="text/css" media="print">
    .container-fluid{
        padding-left:0px;
        padding-right:0px;
    }
</style>
<table width="100%" class="print-header">
                        <tr>
                            <td rowspan="3" style="text-align: right;padding-left: 0px;vertical-align: top" class="logo" width="55px">
                                <img src="{{asset('images/logo.png')}}"  style="display: inline-block;height:50px">
                            </td>
                            <td style="padding-left: 0px;">
                                <span style="font-size:12pt; font-weight: bold">Don Bosco Technical Institute</span>
                            </td>
                            <td style="text-align: center;font-size:12pt; font-weight: bold">
                                GENERATED SHEET B
                            </td>
                            <td style="text-align: right;font-size:12pt;">
                                DAYS OF SCHOOL: <span id="dos"></span>
                            </td>

                        </tr>
                        <tr>
                            <td style="font-size:10pt;padding-left: 0px;">Chino Roces Ave., Makati City </td>
                            <td style="text-align:center;font-weight: bold;">
                                <b id="sy">SCHOOL YEAR {{$schoolyear->schoolyear}} - {{intval($schoolyear->schoolyear)+1}}</b>
                            </td>    
                            <td style="text-align: right;font-size:12pt;">ADVISER:<span id="adviser"> </span></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td style="text-align: center;font-size:12pt; font-weight: bold"><span id="qtr"></span> GRADING PERIOD</td>
                            <td style="text-align: right;font-size:12pt;">Grade and Section:<span id="year"></span></td>
                        </tr>
                    </table>
<br>
<div class="container-fluid">
    <div style="margin-bottom: 20px" class="no-print">
    <button style='padding-top: 10px;padding-bottom: 10px;' class="btn btn-default" data-toggle="collapse" data-target="#menu" onclick="expand()"><strong>
					<span style="display: block;width: 22px;height: 2px;border-radius: 1px;background-color: gray;" class="icon-bar"></span>
					<span style="margin-top: 4px;display: block;width: 22px;height: 2px;border-radius: 1px;background-color: gray;" class="icon-bar"></span>
					<span style="margin-top: 4px;display: block;width: 22px;height: 2px;border-radius: 1px;background-color: gray;" class="icon-bar"></span>        
        </strong></button>
        <span class="col-md-offset-1" id="quarters">
            <a class="btn btn-default quarter btn-primary" id="1st" onclick="changequarter(1,'FIRST')">1st Quarter</a><a class="btn btn-default quarter" id="2nd" onclick="changequarter(2,'SECOND')">2nd Quarter</a><a class="btn btn-default quarter" id="3rd" onclick="changequarter(3,'THIRD')">3rd Quarter</a><a class="btn btn-default quarter" id="4th" onclick="changequarter(4,'FINAL')">4th Quarter</a>
        </span>
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
                                        <a class="section" onclick="setvar('{{$strandsec->section}}','{{$level->level}}','{{$level->department}}','{{$section->strand}}')"><div style='border-bottom: 1px solid #cccccc;padding-left: 30px'>{{$strandsec->section}}</div></a>
                                    @endif
                                @endforeach                                
                            <!--/ul-->
                        
                    @endif
                </div>
                @else
                <a class="section" onclick="setvar('{{$section->section}}','{{$level->level}}','{{$level->department}}')"><div style='border-bottom: 1px solid #cccccc'>{{$section->section}}</div></a>
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
    <div class="col-md-9" id="display" >
        <h3 style="text-align: center">Select a section</h3>
    </div>
</div>
<script>
var quarter = 1;
var dispQtr ="FIRST";
var dept;
var lvl;
var sec;
var strands;
dos();
$('#qtr').html(dispQtr); 

$("#quarters").on("click", "a.quarter", function(){
        $(this).siblings().removeClass('btn-primary');
        $(this).addClass('btn-primary');
        $(this).blur();
    });

function changequarter(setQuarter,q){
    quarter = setQuarter;
    dispQtr = q;
    $('#qtr').html(dispQtr);
    dos();    
    seeGrade();
}

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

function setvar(section,level,department,strand){
    dept = department;
    strands = strand;
    lvl = level;
    sec = section;
    var yearsec = level+" / "+section;
    $('#year').html(yearsec); 
    adviser();
    seeGrade();
    
}

function seeGrade(){
    
    var arrays ={} ;
    arrays['section'] = sec;
    arrays['level']= lvl;
    arrays['quarter']= quarter;
    arrays['strand']= strands;
    arrays['department']= dept;
    $('#display').html("");
    $.ajax({
            type: "GET", 
            url: "/showgrades",
            data : arrays,
            success:function(data){
                
                $('#display').html(data); 
                
                }
            }); 

}

function seeGradeTvet(course){
    var arrays ={} ;
    arrays['course'] = course;
    
    
        $.ajax({
            type: "GET", 
            url: "/showgradestvet",
            data : arrays,
            success:function(data){
                $('#display').html(data); 
                
                }
            }); 
}

function setAcadRank(section,level){
    
    var arrays ={} ;
    arrays['level'] = level;
    arrays['quarter'] = quarter;
    arrays['section'] = section;
    arrays['strand']= strands;
    
        $.ajax({
            type: "GET", 
            url: "/setacadrank",
            data : arrays,
            success:function(data){
                seeGrade();
                
                }
            }); 
}

function adviser(){
    
    var arrays ={} ;
    arrays['level'] = lvl;
    arrays['section'] = sec;    
    arrays['strand']= strands;
    arrays['department']= dept;
    
            $.ajax({
            type: "GET", 
            url: "/getadviser",
            data : arrays,
            success:function(data){
                $('#adviser').html(data);                
                }
            }); 
}

function dos(){
    
    var arrays ={} ;
    arrays['quarter'] = quarter;
    //$('#dos').html("ddd");
    

            $.ajax({
            type: "GET", 
            url: "/getdays",
            data : arrays,
            success:function(data){
                $('#dos').html(data);                
                }
            }); 

}

function setTechRank(){
    //alert("ok");
    var arrays ={} ;
    arrays['level'] = lvl;
    arrays['quarter'] = quarter;
    arrays['section'] = sec;
    arrays['strand']= strands;
    
        $.ajax({
            type: "GET", 
            url: "/settechrank",
            data : arrays,
            success:function(data){
                seeGrade();
               
                }
            }); 
            
}
</script>
@endsection
