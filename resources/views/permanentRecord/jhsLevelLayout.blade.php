                <table style="font-size: 8pt" width="100%" border="1" cellspacing="0">
                    <tr>
                        <td colspan="6">
                            <?php $grade10info = PermanentRecord::syInfo($idno,'Grade 10'); ?>
                            @if(count($grade10info)>0)
                            <?php 
                            $grade10Grades  = \App\Grade::where('schoolyear',$grade10info->schoolyear)->where('idno',$idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
                            $grade10Rank     = \App\Ranking::where('idno',$idno)->where('schoolyear',$grade10info->schoolyear)->first();
                            ?>
                            <table width='100%' cellspacing='0'>
                                <tr>
                                    <td style='text-align: left'>{{strtoupper($grade10info->level)}} - {{$grade10info->section}}</td>
                                    <td style='text-align: right'>School Year</td>
                                </tr>
                                <tr>
                                    <td style='text-align: left'>School: DON BOSCO - MAKATI</td>
                                    <td style='text-align: right'>{{$grade10info->schoolyear}}-{{$grade10info->schoolyear+1}}</td>
                                </tr>
                            </table>
                            @endif
                        </td>
                    </tr>
                    @if(isset($grade10Grades))
                    <tr style="text-align: center">
                        <td width="60%"><b>SUBJECTS</b></td>
                        <td style="font-size: 7pt">1</td>
                        <td style="font-size: 7pt">2</td>
                        <td style="font-size: 7pt">3</td>
                        <td style="font-size: 7pt">4</td>
                        <td style="font-size: 7pt">Final</td>
                    </tr>
                    <tr>
                        <td colspan="6">ACADEMIC SUBJECTS</td>
                    </tr>
                    @foreach($grade10Grades as $grade)
                        @if($grade->subjecttype == 0)
                        <tr>
                            <td>{{strtoupper($grade->subjectname)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                        </tr>
                        @endif
                    @endforeach
                        <tr style='font-weight: bold'>
                            <td>ACADEMI AVERAGE</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grade10Grades,'Grade 10')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grade10Grades,'Grade 10')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grade10Grades,'Grade 10')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grade10Grades,'Grade 10')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grade10Grades,'Grade 10')}}</td>
                        </tr>

                        <tr>
                            <td>RANK</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_acad_1}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_acad_2}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_acad_3}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_acad_4}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_acad_final}}</td>
                        </tr>
                        <tr>
                            <td colspan="6">TECHNICAL SUBJECTS</td>
                        </tr>
                        @foreach($grade10Grades as $grade)
                            @if($grade->subjecttype == 1)
                            <?php $weight7 = $grade->weighted;?>
                            <tr>
                                <td>{{strtoupper($grade->subjectname)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                            </tr>
                            @endif
                        @endforeach
                            <tr style='font-weight: bold'>
                                <td>TECHNICAL AVERAGE</td>
                                @if($weight7 > 0)
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),1,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),2,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),3,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),4,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),5,$grade10Grades,'Grade 10')}}</td>
                                @else
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grade10Grades,'Grade 10')}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>RANK</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_tech_1}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_tech_2}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_tech_3}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_tech_4}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_tech_final}}</td>
                            </tr>
                            <tr ><td colspan="6" style="padding-left: 45px;padding-top: 10pt;height:10pt;"></td></tr>
                            <?php 
                            $dayp = array();
                            $daya = array();
                            $dayt = array();
                            for($i=1; $i < 5 ;$i++){
                                $attendance  = AttendanceController::studentQuarterAttendance($idno,$grade10info->schoolyear,$i,'Grade 10'); 
                                $dayp [] = $attendance[0];
                                $daya [] = $attendance[1];
                                $dayt [] = $attendance[2];
                            }
                            ?>
                            <tr>
                                <td>DAYS OF SCHOOL</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[1]+$daya[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[2]+$daya[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[3]+$daya[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]+$dayp[1]+$daya[1]+$dayp[2]+$daya[2]+$dayp[3]+$daya[3]}}</td>
                            </tr>
                            <tr>
                                <td>DAYS ABSENT</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[0]+$daya[1]+$daya[2]+$daya[3]}}</td>
                            </tr>
                            <tr>
                                <td>DAYS TARDY</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[0]+$dayt[1]+$dayt[2]+$dayt[3]}}</td>
                            </tr>
                        @endif
                </table>