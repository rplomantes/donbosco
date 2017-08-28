<?php 
$withacad = App\GradesSetting::where('level',$level)->where('schoolyear',$sy)->where('subjecttype',0)->exists();
$withtech = App\GradesSetting::where('level',$level)->where('schoolyear',$sy)->where('subjecttype',1)->exists();
if($strand == "null"){
    $subjects = App\CtrSubjects::where('level',$level)->whereIn('semester',array(0,1,5,6))->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
}else{
    $subjects = App\CtrSubjects::  where('level',$level)->where('strand',$strand)->orderBy('subjecttype','ASC')->whereIn('semester',array(1,0))->orderBy('sortto','ASC')->get();
}

?>
<table class="table table-bordered">
    <tr>
        <td>CN</td>
        <td>Student Name</td>
        @if($withacad)
        
        @endif
    </tr>
</table>