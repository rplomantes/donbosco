<?php
use App\Http\Controllers\Registrar\GradeComputation;
use App\Http\Controllers\Registrar\Ranking\SectionRanking;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
$acad = 0;
$tech = 0;
?>

<table class="table table-bordered" id="table" style="font-size: 9pt;">
    <tr style="text-align: center">
        <td>Section</td>
        <td>Student Name</td>
        @if(count($subjects) > 0)
        
            @foreach($subjects as $subject)
                @if(in_array($subject->subjecttype,array(0,5,6)))
                <?php $acad++;?>
                <td>{{$subject->subjectcode}}</td>
                @endif
            @endforeach
            @if($acad > 0)
            <td>ACAD GEN AVE</td>
            <td>ACAD RANK</td>
            @endif
            
            @foreach($subjects as $subject)
                @if(in_array($subject->subjecttype,array(1)))
                <?php $tech++;?>
                <td>{{strtoupper($subject->subjectcode)}}</td>
                @endif
            @endforeach
            
            @if($tech > 0)
            <td>TECH GEN AVE</td>
            <td>TECH RANK</td>
            @endif
            
        @if(in_array($level,array('Grade 11','Grade 12')))
        @endif
	@endif            
    </tr>
    @foreach($students as $student)
    <tr style="text-align: center">
        <td>{{strrchr($student->section," ")}}</td>
        <td style="text-align: left">{{Info::get_name($student->idno)}}
            @if($student->status ==3)
                <span style='float: right;color: red;font-weight: bold'>DROPPED</span>
            @endif
        </td>
        
        @foreach($subjects as $subject)
        
            @if(in_array($subject->subjecttype,array(0,5,6)))
            <td>
                @foreach($student->grade as $grade)
                    @if($grade->subjectcode == $subject->subjectcode)
                    
                        @if($grade->$gradeField > 0)
                        {{round($grade->$gradeField,0)}}
                        @endif
                    
                    @endif
                @endforeach
                </td>
            @endif
        
        @endforeach
        
            @if($acad > 0)
            <td><b>{{GradeComputation::computeQuarterAverage($sy,$level,array(0,5,6),$semester,$gradeQuarter,$student->grade)}}</b></td>
            <td><b>{{SectionRanking::getStudentRank($student->idno,$sy,$acad_field)}}</b></td>
            @endif
            
        @foreach($subjects as $subject)
            @if(in_array($subject->subjecttype,array(1)))
                @foreach($student->grade as $grade)
                    @if($grade->subjectcode == $subject->subjectcode)
                    <td>
                        @if($grade->$gradeField > 0)
                        {{round($grade->$gradeField,0)}}
                        @endif
                    </td>
                    @endif
                @endforeach
            @endif
        @endforeach
        
            @if($tech > 0)
            <td>{{GradeComputation::computeQuarterAverage($sy,$level,array(1),$semester,$gradeQuarter,$student->grade)}}</td>
            <td>{{SectionRanking::getStudentRank($student->idno,$sy,$tech_field)}}</td>
            @endif

            @if(in_array($level,array('Grade 11','Grade 12')))
            @endif
    </tr>
    @endforeach
</table>

<?php
if($sort == "name"){
    $order = 1;
}elseif($sort == "acad"){
    $order = $acad+3;
}elseif($sort == "tech"){
    $order = $tech+$acad+5;
}else{
    $order = 1;
}
?>
<script>
    

  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("table");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[{{$order}}];
      y = rows[i + 1].getElementsByTagName("TD")[{{$order}}];
      //check if the two rows should switch place:
      @if($sort == "name")
          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase())
      @else
          if (parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) {
      @endif
        //if so, mark as a switch and break the loop:
        shouldSwitch= true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }

</script>
