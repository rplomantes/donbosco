<table border='1' cellpadding='1' cellspacing='2' width='100%' style="text-align: center">
    <thead>
    <tr>
        <td style='width:50px;text-align:center;'>CN</td>
        <td style='width:400px;text-align:center;'>Student Name</td>
        @foreach($subjects as $subject)
        @if($subject->subjecttype == 0)
            <td td style='width:50px;text-align:center;'>{{$subject->subjectcode}}</td>
        @endif
        @endforeach
        <td><b>ACAD GEN AVE</b></td>
      
        <td><button class="btn btn-default" onclick="setAcadRank('{{$section}}','{{$level}}',5)">RANK</button></td>
        
        @foreach($subjects as $subject)
            @if($subject->subjecttype == 1)
                <td td style='width:50px;text-align:center;'>{{$subject->subjectcode}}</td>
            @endif
        @endforeach
        <td><b>TECH GEN AVE</b></td>  
        <td style='width:50px;font-weight: bold;text-align:center;'><button class="btn btn-default" onclick="setTechRank()" >TECH<br>RANK</button></td>
        <td><b>GMRC</b></td>    
        @foreach($subjects as $subject)
            @if($subject->subjecttype == 2)
                <td>{{$subject->subjectcode}}</td>
            @endif
        @endforeach
    </tr>
    </thead>
    @foreach($students as $student)
    <tr>
        <?php $info = \App\User::where('idno',$student->idno)->first(); ?>
        
        <td>{{$student->class_no}}</td>
        <td style='font-size: 9pt;padding-left:5px;text-align: left'>{{strtoupper($info->lastname)}} ,{{strtoupper($info->firstname)}} {{strtoupper($info->middlename)}}
            @if($student->status ==3)
        <span style='float: right;color: red;font-weight: bold'>DROPPED</span>
        @endif
        </td>
        
        <?php 
            $grades = \App\Grade::where('idno',$student->idno)->where('isdisplaycard',1)->orderBy('sortto')->get(); 
            $divby = 0;
            $totalave = 0;
        ?>
        
        <!--ACADEMIC-->
        @foreach($grades as $grade)
            @if($grade->subjecttype == 0)
                <?php $total = ($grade->first_grading+$grade->second_grading+$grade->third_grading+$grade->fourth_grading)/4;
                $divby++;
                $totalave = $totalave+$total;
                ?>
                <td>{{number_format(round($total,2),2)}}</td>
            @endif
        @endforeach
        
        <td style='text-align:center;font-weight: bold;'>{{number_format(round($totalave/$divby,2),2)}}</td>
        <!--END ACADEMIC-->
        
        <!--ACADEMIC RANK-->
        <?php $rankings = App\Ranking::where('idno',$student->idno)->first();?>
        <td >
            @if($rankings->acad_final == 0)
            No Rank
            @else
            {{$rankings->acad_final}}
            @endif
        </td>
        <!-- END ACADEMIC RANK-->
        
        <!-- TECH-->
        <?php $totalweight = 0;?>
        @foreach($grades as $grade)
            @if($grade->subjecttype == 1)
                <?php $weight=$grade->weighted / 100;?>
                <?php $totaltech = ($grade->first_grading+$grade->second_grading+$grade->third_grading+$grade->fourth_grading)/4;
                
                $totalweight = $totalweight+(round($totaltech,2) * $weight);
                ?>            
            
                <td>{{number_format(round($totaltech,0),0)}}</td>
            @endif
        @endforeach
        
        <td style='text-align:center;font-weight: bold;'>{{round($totalweight,0)}}</td>
        <!-- END TECH-->

        <!--ACADEMIC RANK-->
        <?php $techrankings = App\Ranking::where('idno',$student->idno)->first();?>
        <td >
            @if($techrankings->tech_final == 0)
            No Rank
            @else
            {{$techrankings->tech_final}}
            @endif
        </td>
        <!-- END ACADEMIC RANK-->
        
        <!--CONDUCT-->
        <?php             
        $divby = 0;
        $totalcon = 0;
        $first = 0;
        $second = 0;
        $third = 0;
        $fourth = 0;
        ?>
        @foreach($grades as $grade)
            @if($grade->subjecttype == 3)
                <?php $conduct = ($grade->first_grading+$grade->second_grading+$grade->third_grading+$grade->fourth_grading)/4;
                
                $first = $first+$grade->first_grading;
                $second = $second+$grade->second_grading;
                $third = $third+$grade->third_grading;
                $fourth = $fourth+$grade->fourth_grading;
                
                ?>
            @endif
        @endforeach
        
        @if($level == 'Grade 7'||$level == 'Grade 8'||$level == 'Grade 9'||$level == 'Grade 10'||$level == 'Grade 11'||$level == 'Grade 12')
                <td style='text-align:center;font-weight: bold;'>{{number_format(round(($first+$second+$third+$fourth)/4,0),0)}}</td>
        @else
                <td style='text-align:center;font-weight: bold;'>{{number_format(round(($first+$second+$third+$fourth)/4,2),2)}}</td>
        @endif 
        <!--END CONDUCT-->
        
        
        <!--ATTENDANCE-->
        <?php $atts = \App\Attendance::where('idno',$student->idno)->get(); ?>
        @foreach($atts as $att)
                <?php $total = $att->Jul+$att->Aug+$att->Sept+$att->Oct+$att->Nov+$att->Dece+$att->Jan+$att->Feb+$att->Mar+$att->Jun;
                $divby++;
                $totalave = $totalave+$total;
                ?>
                <td>{{round($total,1)}}</td>
        @endforeach
        <!--END ATTENDANCE-->

        
    </tr>
    
    @endforeach
</table>