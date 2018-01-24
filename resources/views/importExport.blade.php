@extends('app')
@section('content')
	<div class="container">
<!--
		<a href="{{ URL::to('downloadExcel/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>

		<a href="{{ URL::to('downloadExcel/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>

		<a href="{{ URL::to('downloadExcel/csv') }}"><button class="btn btn-success">Download CSV</button></a>
-->

<?php


?>
<div class="col-md-3">
		<form action="{{ URL::to('importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!} 
                        <div class="form form-group">
			<input type="file" name="import_file" class="form"/>
                        </div>
                        <div class="form form-group">
			<button class="btn btn-primary">Import Grade</button>
                        </div>    
		</form>
</div>

<div class="col-md-9">
     <ul>
    <?php
    $levels = \App\CtrLevel::orderBy('id')->get();
   
    foreach($levels as $level){
        echo "<li>" . $level->level . myFunction($level->level) ."</li>";
    }
    ?>
    </ul>
</div>    
	</div>


<?php
function myFunction($level){
$sections=  DB::Select("select distinct section from statuses where level='$level' and status = '2' ");
$data = "<ul>";
foreach($sections as $section){
    if($section->section == ""){}else{
    $data = $data. "<li>" . $section->section . getSubject($level,$section->section)."</li>";
}}
$data = $data."</ul>";

return $data;    
}

function getSubject($level,$section){
    if($level == 'Grade 11'){
     $subjects = DB::Select("select distinct subjectcode, subjectname from ctr_subjects where level='$level' and subjecttype <= '6' and subjecttype > 4  order by subjecttype, sortto");
    }
    else{
    $subjects = DB::Select("select distinct subjectcode, subjectname from ctr_subjects where level='$level' and subjecttype <= '1'  order by subjecttype, sortto");
    }
    $data = "<ul>";
foreach($subjects as $subject){
    $data = $data. "<li>" . $subject->subjectcode . " - " .$subject->subjectname." - ".  getCount($level, $subject->subjectcode, $section). "</li>";
}
$data = $data."</ul>";

return $data; 
}

function getCount($level, $subjectcode, $section){
  $count = DB::Select("select idno from subject_repos where level = '$level' and subjectcode = '$subjectcode' and section = '$section'");  

  return count($count);
}

?>

@stop
