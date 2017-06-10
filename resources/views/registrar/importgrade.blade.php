@extends('app')
@section('content')
	<div class="container">
<!--
		<a href="{{ URL::to('downloadExcel/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>

		<a href="{{ URL::to('downloadExcel/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>

		<a href="{{ URL::to('downloadExcel/csv') }}"><button class="btn btn-success">Download CSV</button></a>
-->

<?php
$qtr = \App\CtrQuarter::first();
$qtrperiod = $qtr->qtrperiod;

?>
<div class="col-md-3">
		<form action="{{ URL::to('importGrade') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!} 
                        <div class="form form-group">
			<input type="file" name="import_file" class="form"/>
                        </div>
                        <div class="form form-group">
			<button class="btn btn-primary">Import Grade</button>
                        </div>    
		</form>
    
                <form action="{{ URL::to('importConduct') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!} 
                        <div class="form form-group">
			<input type="file" name="import_file1" class="form"/>
                        </div>
                        <div class="form form-group">
			<button class="btn btn-primary">Import Conduct</button>
                        </div>    
		</form>
    
                <form action="{{ URL::to('importAttendance') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!} 
                        <div class="form form-group">
			<input type="file" name="import_file2" class="form"/>
                        </div>
                        <div class="form form-group">
			<button class="btn btn-primary">Import Attendance</button>
                        </div>    
		</form>
    
                <form action="{{ URL::to('importCompetence') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!} 
                        <div class="form form-group">
			<input type="file" name="import_file3" class="form"/>
                        </div>
                        <div class="form form-group">
			<button class="btn btn-primary">Import Competence</button>
                        </div>    
		</form>
</div>

<div class="col-md-3">
    <h5>Grades</h5>
     <ul>
    <?php
    $levels = \App\CtrLevel::orderBy('id')->get();
    foreach($levels as $level){
        echo "<li>" . $level->level . myFunction($level->level,1,$qtrperiod) ."</li>";
    }
    ?>
    </ul>
</div> 
<div class="col-md-3">
    <h5>Conduct</h5>
     <ul>
    <?php
    $levels = \App\CtrLevel::orderBy('id')->get();
   
    foreach($levels as $level){
        echo "<li>" . $level->level . myFunction($level->level,2,$qtrperiod) ."</li>";
    }
    ?>
    </ul>
</div>
<div class="col-md-3">
    <h5>Attendance</h5>
     <ul>
    <?php
    $levels = \App\CtrLevel::orderBy('id')->get();
   
    foreach($levels as $level){
        echo "<li>" . $level->level . myFunction($level->level,3,$qtrperiod) ."</li>";
    }
    ?>
</ul>
    
    <h5>Competencies</h5>
     <ul>
    <?php
    
        echo "<li> Kindergarten" . getCompetency($qtrperiod) ."</li>";
    
    ?>
    </ul>
</div>
	</div>


<?php
function getCompetency($qtrperiod){
$data = "<ul>"; 
$sections=  DB::Select("select distinct section from statuses where level='Kindergarten' and status = '2' ");
foreach($sections as $section){
    if($section->section == ""){}else{
    $data = $data. "<li>" . $section->section . getCompetencyValue($section->section,$qtrperiod)."</li>";
}}

$data = $data."</ul>";

return $data; 
}
function myFunction($level,$subjecttype,$qtrperiod){
$data = "<ul>"; 
$sections=  DB::Select("select distinct section from statuses where level='$level' and status = '2' ");
foreach($sections as $section){
    if($section->section == ""){}else{
    $data = $data. "<li>" . $section->section . getSubject($level,$section->section,$subjecttype,$qtrperiod)."</li>";
}}

$data = $data."</ul>";

return $data;    
}

function getCompetencyValue($section,$qtrperiod){
    $values = DB::Select("select distinct competencycode from ctr_competences");
    $data = "<ul>";
    foreach($values as $value){
        $mycount=DB::Select("select a.idno from statuses as a, competency_repos as b where a.idno=b.idno "
                . " and a.level='Kindergarten' and a.section='$section' and b.qtrperiod='$qtrperiod'");
        $data=$data."<li";
        if(count($mycount)>0){
            $data = $data . " style='color:red'";    
        }
        $data = $data.">" .$value->competencycode . " - " . count($mycount);
    }
    $data=$data."</ul>";
    return $data;
}
function getSubject($level,$section,$subjecttype,$qtrperiod){
  if($subjecttype=='1'){  
    if($level == 'Grade 11'){
     $subjects = DB::Select("select distinct subjectcode, subjectname from ctr_subjects where level='$level' and subjecttype <= '6' and subjecttype > 4  order by subjecttype, sortto");
    }
    else{
    $subjects = DB::Select("select distinct subjectcode, subjectname from ctr_subjects where level='$level' and subjecttype <= '1'  order by subjecttype, sortto");
  }}
  elseif($subjecttype=='2'){
     $subjects = DB::Select("select distinct subjectcode, subjectname from ctr_subjects where level='$level' and subjecttype = '3'  order by subjecttype, sortto");
  }
  elseif($subjecttype=='3'){
     $subjects = DB::Select("select distinct subjectcode, subjectname from ctr_subjects where level='$level' and subjecttype = '2'  order by subjecttype, sortto"); 
  }
    $data = "<ul>";
foreach($subjects as $subject){
    $mycount = getCount($level, $subject->subjectcode, $section,$subjecttype, $qtrperiod);
    $data = $data. "<li";
    if($mycount > 0 ){
        $data=$data . " style=\"color:red\" ";
    }
        $data=$data. ">" . $subject->subjectcode . " - " .$subject->subjectname." - $mycount </li>";
}
$data = $data."</ul>";

return $data; 
}

function getCount($level, $subjectcode, $section,$subjecttype,$qtrperiod){
  if($subjecttype=='1'){  
  $count = DB::Select("select subject_repos.idno from subject_repos,statuses  where  subject_repos.idno=statuses.idno and statuses.level = '$level' and subject_repos.subjectcode = '$subjectcode' and statuses.section = '$section' and subject_repos.qtrperiod = '$qtrperiod'");  
  }
  elseif($subjecttype=='2'||$subjecttype=='3'){
  if($subjecttype=='2'){    
  $count = DB::Select("Select conduct_repos.idno from conduct_repos,statuses where conduct_repos.idno = statuses.idno and statuses.level = '$level' and statuses.section = '$section' and conduct_repos.qtrperiod='$qtrperiod'");
  } else{
   $count = DB::Select("Select attendance_repos.idno from attendance_repos,statuses where attendance_repos.idno = statuses.idno and statuses.level = '$level' and statuses.section = '$section' and attendance_repos.qtrperiod = '$qtrperiod'");    
  }
  
  }
  return count($count);
}

?>

@stop
