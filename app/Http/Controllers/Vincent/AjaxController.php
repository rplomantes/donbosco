<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Support\Facades\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;
class AjaxController extends Controller
{
    public function getid($varid){
        if(Request::ajax()){
        $user = \App\User::find($varid);
        $refno = $user->reference_number;
        $varrefno = strval($refno);
        $user->reference_number = $refno + 1;
        $user->update(); 
        
        $sy = \App\RegistrarSchoolyear::first(); 
            for($i=strlen($varrefno); $i< 3 ;$i++){
                $varrefno = "0" . $varrefno;    
            }
            
            $value = substr($sy->schoolyear,2,2) . $user->idref . $varrefno;
            $intval = 0;
            
            for($y=1; $y<= strlen($value); $y++){
                $sub = substr($value,$y);
                $intval = $intval + intval($sub);
            }
              //$intval = $intval%9;
              $varrefno = $value . strval($intval%9); 
            
         // return $user->idref;  
        return $varrefno;
        }else{
        return "Invalid Request";    
        }
    }
    
    function showgrades(){
        
        $section = Input::get('section');
        $level = Input::get('level');
        $strand = Input::get('strand');
        $department = Input::get('department');
        $quarters = Input::get('quarter');

        $this->acadRank($section,$level,$quarters);
        if(Input::get('department') == 'Junior High School'){
        $this->techRank($section,$level,$quarters,$strand);
        }
        
        $students = DB::Select("Select statuses.idno,class_no,gender,lastname,firstname,middlename,extensionname,statuses.status from users left join statuses on users.idno = statuses.idno where statuses.status IN (2,3) and statuses.level = '$level' and statuses.section = '$section' AND statuses.strand = '$strand' order by class_no ASC");
        //$students = DB::Select("Select statuses.idno,gender,lastname,firstname,middlename,extensionname from users left join statuses on users.idno = statuses.idno where statuses.status= 2 and statuses.level = 'Grade 10' and statuses.section = 'Saint Callisto Caravario' AND statuses.strand = 'Industrial Drafting Technology' order by gender DESC,lastname ASC,firstname ASC");
        if(Input::get('department') != 'Senior High School'){
            $strand = '';
        }
        
        if(Input::get('department') == 'Senior High School' && ($quarters == 1 ||$quarters == 2)){
            $subjects = \App\CtrSubjects::where('level',Input::get('level'))->where('strand',$strand)->orderBy('subjecttype','ASC')->whereIn('semester',array(1,0))->orderBy('sortto','ASC')->get();
        }
        elseif(Input::get('department') == 'Senior High School' && ($quarters == 3 ||$quarters == 4)){
            $subjects = \App\CtrSubjects::where('level',Input::get('level'))->where('strand',$strand)->orderBy('subjecttype','ASC')->whereIn('semester',array(2,0))->orderBy('sortto','ASC')->get();
        }else{
            $subjects = \App\CtrSubjects::where('level',Input::get('level'))->where('strand',$strand)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
        }
        
            //$subjects = \App\CtrSubjects::where('level',Input::get('level'))->where('strand',$strand)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
        $schoolyear = \App\CtrRefSchoolyear::first();
        $count = 1;
        $sy = $schoolyear->schoolyear;
        $data = "";
        
        $data = $data."<table border='1' cellpadding='1' cellspacing='2' width='100%'>";
        $data = $data."<tr>";
        $data = $data."<thead>";
        $data = $data."<td style='width:50px;text-align:center;'>CN</td>";
        $data = $data."<td style='width:400px;text-align:center;'>Student Name</td>";
        
            foreach($subjects as $subj){
                if($subj->subjecttype == 0){
                    $data = $data."<td style='width:50px;text-align:center;'>".$subj->subjectcode."</td>";
                }
            }
            
            foreach($subjects as $subj){
                if($subj->subjecttype == 5 | $subj->subjecttype == 6){
                    $data = $data."<td style='width:50px;text-align:center;'>".$subj->subjectcode."</td>";
                }
            }
            
            $data = $data."<td style='width:80px;font-weight: bold;text-align:center;'>ACAD GEN AVE</td>";
            $data = $data."<td style='width:50px;font-weight: bold;text-align:center;'><button class='btn btn-default' onclick=\"setAcadRank('".$section."','".$level."',".Input::get('quarter').")\" >RANK</button></td>";
            foreach($subjects as $subj){
                if($subj->subjecttype == 1){
                    $data = $data."<td style='width:50px;text-align:center;'>".$subj->subjectcode."</td>";
                    }
            }
            if(Input::get('department') == 'Junior High School'){
            $data = $data."<td style='width:50px;font-weight: bold;text-align:center;'>TWA</td>";
            $data = $data."<td style='width:50px;font-weight: bold;text-align:center;'><button class='btn btn-default' onclick=\"setTechRank()\" >TECH<br>RANK</button></td>";
            }
            
            $data = $data."<td style='width:50px;font-weight: bold;text-align:center;'>GMRC</td>";
            
            foreach($subjects as $subj){
                if($subj->subjecttype == 2){
                $data = $data."<td style='width:50px;text-align:center;'>".$subj->subjectcode."</td>";
                }
            }
            $data = $data."</tr>";

            $data = $data."</thead>";
            foreach($students as $student){
            $data = $data."<tr>";
            $data = $data."<td style='text-align:center;'>".$student->class_no."</td>";
            $data = $data."<td style='font-size: 9pt;padding-left:5px;'>".$student->lastname.", ".$student->firstname." ".$student->middlename." ".$student->extensionname;
            if($student->status == 3){
                $data = $data."<span style='float: right;color: red;font-weight: bold'>DROPPED</span>";
            }
            $data = $data."</td>";
            
  
            switch (Input::get('quarter')){
                    case 1;
                        $grades = \App\Grade::select('subjecttype','first_grading as grade') ->where('idno',$student->idno)->where('schoolyear',$sy)->where('isdisplaycard',1)->whereIn('semester',array(1,0))->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                        $ranking = \App\Ranking::select('acad_1 as acad','tech_1 as tech')->where('idno',$student->idno)->where('schoolyear',$sy)->first();
                            $month1 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month','JUN')->orderBy('id','DESC')->first();
                            $month2 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month','JUL')->orderBy('id','DESC')->first();
                            $month3 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month','AUG')->orderBy('id','DESC')->first();
                            if(!empty($month1) && !empty($month2) && !empty($month3)){
                                $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                                $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                                $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                            }else{
                                $dayt = 0;
                                $dayp = 0;
                                $daya = 0;
                            }
                    break;
                    case 2;
                        $grades = \App\Grade::select('subjecttype','second_grading as grade')->where('idno',$student->idno)->where('schoolyear',$sy)->where('isdisplaycard',1)->whereIn('semester',array(1,0))->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                        $ranking = \App\Ranking::select('acad_2 as acad','tech_2 as tech')->where('idno',$student->idno)->where('schoolyear',$sy)->first();
                            $month1 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"Sept")->orderBy('id','DESC')->first();
                            $month2 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"OCT")->orderBy('id','DESC')->first();
                            $month3 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"AUG")->orderBy('id','DESC')->first();
                            
                            if(!empty($month1) && !empty($month2) && !empty($month3)){
                                $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                                $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                                $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                            }else{
                                $dayt = 0;
                                $dayp = 0;
                                $daya = 0;
                            }
                    break;                
                    case 3;
                        $grades = \App\Grade::select('subjecttype','third_grading as grade')->where('idno',$student->idno)->where('schoolyear',$sy)->where('isdisplaycard',1)->whereIn('semester',array(2,0))->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                        $ranking = \App\Ranking::select('acad_3 as acad','tech_3 as tech')->where('idno',$student->idno)->where('schoolyear',$sy)->first();
                        if($level == "Grade 11" || $level == "Grade 12"){
                            $month1 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"OCT")->orderBy('id','DESC')->first();
                            $month2 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"NOV")->orderBy('id','DESC')->first();
                            $month3 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"DECE")->orderBy('id','DESC')->first();
                        }else{
                            $month1 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"NOV")->orderBy('id','DESC')->first();
                            $month2 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"DECE")->orderBy('id','DESC')->first();
                            $month3 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"JAN")->orderBy('id','DESC')->first();                            
                        }
                            if(!empty($month1) && !empty($month2) && !empty($month3)){
                                $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                                $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                                $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                            }else{
                                $dayt = 0;
                                $dayp = 0;
                                $daya = 0;
                            }
                    break;
                    case 4;
                        $grades = \App\Grade::select('subjecttype','fourth_grading as grade')->where('idno',$student->idno)->where('schoolyear',$sy)->where('isdisplaycard',1)->whereIn('semester',array(2,0))->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                        $ranking = \App\Ranking::select('acad_4 as acad','tech_4 as tech')->where('idno',$student->idno)->where('schoolyear',$sy)->first();
                            $month1 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"JAN")->orderBy('id','DESC')->first();
                            $month2 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"FEB")->orderBy('id','DESC')->first();
                            $month3 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"MAR")->orderBy('id','DESC')->first();
                            
                            if(!empty($month1) && !empty($month2) && !empty($month3)){
                                $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                                $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                                $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                            }else{
                                $dayt = 0;
                                $dayp = 0;
                                $daya = 0;
                            }
                    break;                
                }
                if($grades->isEmpty()){}
                else{
                foreach($grades as $grade){
                    if($grade->subjecttype == 0){
                        $data = $data."<td style='text-align:center;'>".round($grade->grade,0)."</td>";
                    }
                }
                if($subjects[0]->subjecttype == 0){
                    if(Input::get('department') == 'Junior High School'){
                        $data = $data."<td style='text-align:center;font-weight: bold;'>".round($this->calcGrade(0,(int)Input::get('quarter'),$student->idno,$sy),0)."</td>";
                    }else{
                        $data = $data."<td style='text-align:center;font-weight: bold;'>".number_format(round($this->calcGrade(0,(int)Input::get('quarter'),$student->idno,$sy),2),2)."</td>";
                    }
                }
                foreach($grades as $grade){
                    if($grade->subjecttype == 5 || $grade->subjecttype == 6){
                        $data = $data."<td style='text-align:center;'>".round($grade->grade,0)."</td>";
                        
                    }
                }
                
                if(Input::get('department') == 'Senior High School'){
                    $data = $data."<td style='text-align:center;font-weight: bold;'>".round($this->calcSeniorGrade((int)Input::get('quarter'),$student->idno,$sy),0)."</td>";
                }                                
                
                if(is_null($ranking)|| $ranking->acad == 0){
        
                    $data = $data."<td style='text-align:center;'>No rank set</td>";
                    //$data = $data."<td style='text-align:center;'> </td>";
                }else{
                    $data = $data."<td style='text-align:center;'>".$ranking->acad."</td>";
                    //$data = $data."<td style='text-align:center;'> </td>";
                }

                
                foreach($grades as $grade){
                if($grade->subjecttype == 1){
                $data = $data."<td style='text-align:center;'>".round($grade->grade,0)."</td>";
                }
                }
                if(Input::get('department') == 'Junior High School'){
                $data = $data."<td style='text-align:center;font-weight: bold;'>".round($this->calcGrade(1,(int)Input::get('quarter'),$student->idno,$sy),0)."</td>";
                //$data = $data."<td style='text-align:center;font-weight: bold;'>".(int)Input::get('quarter')."</td>";
                if(is_null($ranking)|| $ranking->tech == 0){
        
                    $data = $data."<td style='text-align:center;'>No rank set</td>";
                    //$data = $data."<td style='text-align:center;'> </td>";
                }else{
                    $data = $data."<td style='text-align:center;'>".$ranking->tech."</td>";
                    //$data = $data."<td style='text-align:center;'> </td>";
                }                
               }
                
                $conduct = 0;
                foreach($grades as $grade){
                    if($grade->subjecttype == 3){
                    $conduct = $conduct+$grade->grade;
                    }
                }
                //if(Input::get('department') == 'Senior High School' ||Input::get('department') == 'Junio High School'){
                    $data = $data."<td style='text-align:center;font-weight: bold;'>".round($conduct,0)."</td>";
                //}else{
                //    $data = $data."<td style='text-align:center;font-weight: bold;'>".number_format(round($conduct,2),2)."</td>";
                    
                //}
                
                
                $data = $data."<td style='text-align:center;'>".number_format($dayp,1)."</td>";
                $data = $data."<td style='text-align:center;'>".number_format($daya,1)."</td>";
                $data = $data."<td style='text-align:center;'>".number_format($dayt,1)."</td>";
            }
            $data = $data."<tr>";
                $count++;
            
            }
        $data = $data."</table>";
        
        return $data;
        //return $strand." ".$level." "$section.;
        
    }
    
    function showgradestvet(){
        
        $course = Input::get('course');

        $students = DB::Select("Select statuses.idno,gender,lastname,firstname,middlename,extensionname from users left join statuses on users.idno = statuses.idno where statuses.status= 2 AND statuses.course LIKE '$course' order by gender DESC,lastname ASC,firstname ASC");

        $subjects = \App\CtrSubjects::where('course',$course)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
        $count = 1;
        $schoolyear = \App\CtrRefSchoolyear::first();
        $sy = $schoolyear->schoolyear;
        $data = "";
        
        $data = $data."<table border='1' cellpadding='1' cellspacing='2'>";
        $data = $data."<tr>";
        $data = $data."<td style='width:30px;text-align:center;'>CN</td>";
        $data = $data."<td style='width:310px;text-align:center;'>Student Name</td>";
        
            foreach($subjects as $subj){
                if($subj->subjecttype == 0){
                    $data = $data."<td style='width:50px;text-align:center;'>".$subj->subjectcode."</td>";
                }
            }

            $data = $data."<td style='width:80px;font-weight: bold;text-align:center;'>ACAD GEN AVE</td>";
            
            foreach($subjects as $subj){
                if($subj->subjecttype == 1){
                    $data = $data."<td style='width:50px;text-align:center;'>".$subj->subjectcode."</td>";
                    }
            }

            $data = $data."<td style='width:50px;font-weight: bold;text-align:center;'>TWA</td>";

            
            $data = $data."<td style='width:50px;font-weight: bold;text-align:center;'>GMRC</td>";
            
            foreach($subjects as $subj){
                if($subj->subjecttype == 2){
                $data = $data."<td style='width:50px;text-align:center;'>".$subj->subjectcode."</td>";
                }
            }
            $data = $data."</tr>";

            
            foreach($students as $student){
            $data = $data."<tr>";
            $data = $data."<td style='text-align:center;'>".$count."</td>";
            $data = $data."<td style='font-size: 9pt'>".$student->lastname.", ".$student->firstname." ".$student->middlename." ".$student->extensionname."</td>";

            
            switch (Input::get('quarter')){
                    case 1;
                        $grades = \App\Grade::select('subjecttype','first_grading as grade') ->where('idno',$student->idno)->where('schoolyear',$sy)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                    break;
                    case 2;
                        $grades = \App\Grade::select('subjecttype','second_grading as grade')->where('idno',$student->idno)->where('schoolyear',$sy)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                    break;                
                    case 3;
                        $grades = \App\Grade::select('subjecttype','third_grading as grade') ->where('idno',$student->idno)->where('schoolyear',$sy)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                    break;
                    case 4;
                        $grades = \App\Grade::select('subjecttype','fourth_grading as grade')->where('idno',$student->idno)->where('schoolyear',$sy)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                    break;                
                }
                
                foreach($grades as $grade){
                    if($grade->subjecttype == 0){
                        $data = $data."<td style='text-align:center;'>".round($grade->grade,0)."</td>";
                        
                    }
                }
                if($subjects[0]->subjecttype == 0){
                $data = $data."<td style='text-align:center;font-weight: bold;'>".$this->calcGrade(0,(int)Input::get('quarter'),$student->idno,$sy)."</td>";
                }

            $data = $data."<tr>";
                $count++;
            
            }
        $data = $data."</table>";
        
        return $data;
        //return $strand." ".$level." "$section.;
        
    }    
    
    public function calcGrade($type,$quarter,$idno,$sy){
            switch ($quarter){
                    case 1;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( first_grading ) / count( idno ) , 2 ) AS average FROM `grades` WHERE subjecttype =$type AND idno = '$idno' AND schoolyear = '$sy' AND isdisplaycard = 1 GROUP BY idno");
                    break;
                    case 2;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( second_grading ) / count( idno ) , 2 ) AS average FROM `grades` WHERE subjecttype =$type AND idno = '$idno' AND schoolyear = '$sy' AND isdisplaycard = 1 GROUP BY idno");
                    break;                
                    case 3;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( third_grading ) / count( idno ) , 2 ) AS average FROM `grades` WHERE subjecttype =$type AND idno = '$idno' AND schoolyear = '$sy' AND isdisplaycard = 1 GROUP BY idno");
                    break;
                    case 4;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( fourth_grading ) / count( idno ) , 2 ) AS average FROM `grades` WHERE subjecttype =$type AND idno = '$idno' AND schoolyear = '$sy' AND isdisplaycard = 1 GROUP BY idno");
                    break;
                }     
                if($averages[0]->weighted == 0){
                    $result = $averages[0]->average;
                }else{
                    $result = $this->calcWeighted($quarter,$idno,$sy);
                    //$result = 0;
                }                
        return $result;
        
    }
    
    public function calcWeighted($quarter,$idno,$sy){
            switch ($quarter){
                    case 1;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( first_grading *(weighted/100))  , 2 ) AS average FROM `grades` WHERE subjecttype =1 AND idno = '$idno' AND schoolyear = '$sy' AND isdisplaycard = 1 GROUP BY idno");
                    break;
                    case 2;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( second_grading *(weighted/100))  , 2 ) AS average FROM `grades` WHERE subjecttype =1 AND idno = '$idno' AND schoolyear = '$sy' AND isdisplaycard = 1 GROUP BY idno");
                    break;                
                    case 3;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( third_grading *(weighted/100))  , 2 ) AS average FROM `grades` WHERE subjecttype =1 AND idno = '$idno' AND schoolyear = '$sy' AND isdisplaycard = 1 GROUP BY idno");
                    break;
                    case 4;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( fourth_grading *(weighted/100))  , 2 ) AS average FROM `grades` WHERE subjecttype =1 AND idno = '$idno' AND schoolyear = '$sy' AND isdisplaycard = 1 GROUP BY idno");
                    break;                
                }    
                /*$result = $averages[0]->average;
                if($averages[0]->average == 0){
                    $result = 0;
                }*/
        //$averages = DB::Select("SELECT weighted,ROUND( SUM( first_grading *(weighted/100))  , 0 ) AS average FROM `grades` WHERE subjecttype =1 AND idno = '$idno' AND schoolyear = '$sy' GROUP BY idno");
        $result = $averages[0]->average;
        return $result;
        
    }    
    
    public function calcSeniorGrade($quarter,$idno,$sy){
            switch ($quarter){
                    case 1;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( first_grading ) / count( idno ) , 0 ) AS average FROM `grades` WHERE subjecttype IN(5,6) and semester=1 AND idno = '$idno' AND schoolyear = '$sy' GROUP BY idno");
                    break;
                    case 2;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( second_grading ) / count( idno ) , 0 ) AS average FROM `grades` WHERE subjecttype IN(5,6) and semester=1 AND idno = '$idno' AND schoolyear = '$sy' GROUP BY idno");
                    break;                
                    case 3;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( third_grading ) / count( idno ) , 0 ) AS average FROM `grades` WHERE subjecttype IN(5,6) and semester=2 AND idno = '$idno' AND schoolyear = '$sy' GROUP BY idno");
                    break;
                    case 4;
                        $averages = DB::Select("SELECT weighted,ROUND( SUM( fourth_grading ) / count( idno ) , 0 ) AS average FROM `grades` WHERE subjecttype IN(5,6) and semester=2 AND idno = '$idno' AND schoolyear = '$sy' GROUP BY idno");
                    break;
                }     
                if($averages[0]->weighted == 0){
                    $result = $averages[0]->average;
                }else{
                    $result = $this->calcWeighted($quarter,$idno,$sy);
                    //$result = 0;
                }                
        return $result;
        
    }
    
    function setOARank(){
        $level = Input::get('level');
        $quarter = Input::get('quarter');
        $strand = Input::get('strand');
        
        $this->setOARankingAcad($level,$quarter,$strand);
        if($level == "Grade 7" | $level == "Grade 8" |$level == "Grade 9" | $level == "Grade 10"){
            $this->setOARankingTech($level,$quarter);
        }
        
        return "go";
    }
    
    function setOARankingAcad($level,$quarter,$strand){
        switch ($quarter){
            case 1;
                $qrt = "first_grading";
            break;
            case 2;
                $qrt = "second_grading";
            break;                
           case 3;
                $qrt = "third_grading";
            break;
            case 4;
                $qrt = "fourth_grading";
           break; 
        }        

        $schoolyear = \App\CtrRefSchoolyear::first();
        if($level == "Grade 7" || $level == "Grade 8" || $level == "Grade 9" || $level == "Grade 10" || $level == "Grade 11" || $level == "Grade 12"){
            if($level == "Grade 11" || $level == "Grade 12"){
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( $qrt ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND grades.schoolyear = '$schoolyear->schoolyear' AND statuses.strand = '$strand' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` and  DESC");
            }else{
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( $qrt ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` DESC");
            }
        }else{
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( $qrt ) / count( grades.idno ) ,2) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` DESC");
        }
        $ranking = 0;
        $comparison = 0;
        
        $nextrank = 1;
        foreach($averages as $average){
            
            
            $check = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$schoolyear->schoolyear)->get();
            
            if($comparison != $average->average){
                $ranking = $nextrank;
                
                $comparison = $average->average;
            }
            elseif($average->average == 0){
                $ranking = 0;
            } 
            
            
            
            if ($check->isEmpty()) { 
                $rank = new \App\Ranking();
            }else{
                $rank = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$schoolyear->schoolyear)->first();
            }
            
            if($check->isEmpty()){
                $rank->idno = $average->idno;
            }
                switch ($quarter){
                  case 1;
                        $rank->oa_acad_1 = $ranking;
                break;
                    case 2;
                        $rank->oa_acad_2 = $ranking;
                    break;                
                   case 3;
                        $rank->oa_acad_3 = $ranking;
                    break;
                    case 4;
                        $rank->oa_acad_4 = $ranking;
                   break;            
               
                }
            $rank->schoolyear =   $schoolyear->schoolyear;  
            $rank->save();
            $nextrank++;
        }
        
        return $level;
    }
    
    function setOARankingTech($level,$quarter){

        $schoolyear = \App\CtrRefSchoolyear::first();
        
        switch ($quarter){
            case 1;
                $averages = DB::Select("SELECT grades.idno,weighted, ROUND( SUM( first_grading ) / count( grades.idno ) , 2 ) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND grades.schoolyear = '$schoolyear->schoolyear' GROUP BY idno ORDER BY `average` DESC");
            break;
            case 2;
                $averages = DB::Select("SELECT grades.idno,weighted, ROUND( SUM( second_grading ) / count( grades.idno ) , 2 ) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND grades.schoolyear = '$schoolyear->schoolyear' GROUP BY idno ORDER BY `average` DESC");
            break;                
           case 3;
                $averages = DB::Select("SELECT grades.idno,weighted, ROUND( SUM( third_grading ) / count( grades.idno ) , 2 ) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND grades.schoolyear = '$schoolyear->schoolyear' GROUP BY idno ORDER BY `average` DESC");
            break;
            case 4;
                $averages = DB::Select("SELECT grades.idno,weighted, ROUND( SUM( fourth_grading ) / count( grades.idno ) , 2 ) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND grades.schoolyear = '$schoolyear->schoolyear' GROUP BY idno ORDER BY `average` DESC");
           break;                
        }
                if($averages[0]->weighted != 0){
                    $averages = $this->weightedOARank($quarter,$schoolyear->schoolyear,$level);
                }
                
        $ranking = 0;
        $comparison = 0;
        $nextrank = 1;
        foreach($averages as $average){
            $check = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$schoolyear->schoolyear)->get();
                
            if($comparison != $average->average){
                $ranking=$nextrank;
                //$ranking++;
                $comparison = $average->average;
            }
            elseif($average->average == 0){
                $ranking = 0;
            } 
            
            
            
            if ($check->isEmpty()) { 
                $rank = new \App\Ranking();
            }else{
                $rank = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$schoolyear->schoolyear)->first();
            }
            
            if($check->isEmpty()){
                $rank->idno = $average->idno;
            }
                switch ($quarter){
                  case 1;
                        $rank->oa_tech_1 = $ranking;
                break;
                    case 2;
                        $rank->oa_tech_2 = $ranking;
                    break;                
                   case 3;
                        $rank->oa_tech_3 = $ranking;
                    break;
                    case 4;
                        $rank->oa_tech_4 = $ranking;
                   break;            
                   
                }
            $rank->schoolyear =$schoolyear->schoolyear;  
            $rank->save();
            $nextrank++;
        }
        
        return $level;
    }
    
    function weightedOARank($quarter,$schoolyear,$level){
            switch ($quarter){
                    case 1;
                        $averages = DB::Select("SELECT grades.idno,weighted,ROUND( SUM( first_grading *(weighted/100))  , 0 ) AS average FROM `grades` join statuses on statuses.idno = grades.idno WHERE  subjecttype =1 AND grades.level = '$level' AND grades.schoolyear = '$schoolyear' AND statuses.status = 2 GROUP BY idno ORDER BY `average` DESC");
                    break;
                    case 2;
                        $averages = DB::Select("SELECT grades.idno,weighted,ROUND( SUM( second_grading *(weighted/100))  , 0 ) AS average FROM `grades` join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND grades.schoolyear = '$schoolyear' AND statuses.status = 2 GROUP BY idno ORDER BY `average` DESC");
                    break;                
                    case 3;
                        $averages = DB::Select("SELECT grades.idno,weighted,ROUND( SUM( third_grading *(weighted/100))  , 0 ) AS average FROM `grades` join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND grades.schoolyear = '$schoolyear' AND statuses.status = 2 GROUP BY idno ORDER BY `average` DESC");
                    break;
                    case 4;
                        $averages = DB::Select("SELECT grades.idno,weighted,ROUND( SUM( fourth_grading *(weighted/100))  , 0 ) AS average FROM `grades` join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND grades.schoolyear = '$schoolyear' AND statuses.status = 2 GROUP BY idno ORDER BY `average` DESC");
                    break;                
                }    


        return $averages;
        
    }        
    
    function setRankingAcad(){
        $section = Input::get('section');
        $level = Input::get('level');
        $quarter = Input::get('quarter');
        
        $this->acadRank($section,$level,$quarter);
    }
    
    function acadRank($section,$level,$quarter){
        
        $schoolyear = \App\CtrRefSchoolyear::first();
        if($level == "Grade 7" || $level == "Grade 8" || $level == "Grade 9" || $level == "Grade 10"){
        switch ($quarter){
            case 1;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( first_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` DESC");
            break;
            case 2;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( second_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` DESC");
            break;                
           case 3;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( third_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` DESC");
            break;
            case 4;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( fourth_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` DESC");
           break; 
        }
        }elseif($level == "Grade 11" || $level == "Grade 12"){
            switch ($quarter){
                case 1;
                    $averages = DB::Select("SELECT grades.idno,ROUND( SUM( first_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 and semester = 1 GROUP BY idno ORDER BY `average` DESC");
                break;
                case 2;
                    $averages = DB::Select("SELECT grades.idno,ROUND( SUM( second_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 and semester = 1  GROUP BY idno ORDER BY `average` DESC");
                break;                
               case 3;
                    $averages = DB::Select("SELECT grades.idno,ROUND( SUM( third_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 and semester = 2  GROUP BY idno ORDER BY `average` DESC");
                break;
                case 4;
                    $averages = DB::Select("SELECT grades.idno,ROUND( SUM( fourth_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 and semester = 2  GROUP BY idno ORDER BY `average` DESC");
               break; 
            }
        }
        else{
        switch ($quarter){
            case 1;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( first_grading ) / count( grades.idno ) ,2) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` DESC");
            break;
            case 2;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( second_grading ) / count( grades.idno ) ,2) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` DESC");
            break;                
           case 3;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( third_grading ) / count( grades.idno ) ,2) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` DESC");
            break;
            case 4;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( fourth_grading ) / count( grades.idno ) ,2) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND isdisplaycard = 1 GROUP BY idno ORDER BY `average` DESC");
           break; 
        }            
        }
        $ranking = 0;
        $comparison = 0;
        
        $nextrank = 1;
        foreach($averages as $average){
            
            
            $check = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$schoolyear->schoolyear)->get();
            
            if($comparison != $average->average){
                $ranking = $nextrank;
                
                $comparison = $average->average;
            }
            elseif($average->average == 0){
                $ranking = 0;
            } 
            
            
            
            if ($check->isEmpty()) { 
                $rank = new \App\Ranking();
            }else{
                $rank = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$schoolyear->schoolyear)->first();
            }
            
            if($check->isEmpty()){
                $rank->idno = $average->idno;
            }
                switch ($quarter){
                  case 1;
                        $rank->acad_1 = $ranking;
                break;
                    case 2;
                        $rank->acad_2 = $ranking;
                    break;                
                   case 3;
                        $rank->acad_3 = $ranking;
                    break;
                    case 4;
                        $rank->acad_4 = $ranking;
                   break;            
               
                }
            $rank->schoolyear =   $schoolyear->schoolyear;  
            $rank->save();
            $nextrank++;
        }
        
        return $level;        
    }
    
    function setRankingTech(){
        $section = Input::get('section');
        $level = Input::get('level');
        $quarter = Input::get('quarter');
        $strand = Input::get('strand');
        
        $this->techRank($section,$level,$quarter,$strand);
    }
    
    function techRank($section,$level,$quarter,$strand){
        
        $schoolyear = \App\CtrRefSchoolyear::first();
        
        switch ($quarter){
            case 1;
                //$averages = DB::Select("SELECT idno,weighted, ROUND( SUM( first_grading ) / count( idno ) , 0 ) AS average FROM `grades` WHERE subjecttype =0 AND level = '$level' AND section LIKE '$section' AND schoolyear = '$schoolyear->schoolyear' GROUP BY idno ORDER BY `average` DESC");
                $averages = DB::Select("SELECT grades.idno,weighted, ROUND( SUM( first_grading ) / count( grades.idno ) , 2 ) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND statuses.strand = '$strand' GROUP BY idno ORDER BY `average` DESC");
            break;
            case 2;
                $averages = DB::Select("SELECT grades.idno,weighted, ROUND( SUM( second_grading ) / count( grades.idno ) , 2 ) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND statuses.strand = '$strand' GROUP BY idno ORDER BY `average` DESC");
            break;                
           case 3;
                $averages = DB::Select("SELECT grades.idno,weighted, ROUND( SUM( third_grading ) / count( grades.idno ) , 2 ) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND statuses.strand = '$strand' GROUP BY idno ORDER BY `average` DESC");
            break;
            case 4;
                $averages = DB::Select("SELECT grades.idno,weighted, ROUND( SUM( fourth_grading ) / count( grades.idno ) , 2 ) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear->schoolyear' AND statuses.strand = '$strand' GROUP BY idno ORDER BY `average` DESC");
           break;                
        }
                if($averages[0]->weighted != 0){
                    $averages = $this->weightedRank($quarter,$schoolyear->schoolyear,$strand,$level,$section);
                }
                
        $ranking = 0;
        $comparison = 0;
        $nextrank = 1;
        foreach($averages as $average){
            $check = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$schoolyear->schoolyear)->get();
                
            if($comparison != $average->average){
                $ranking=$nextrank;
                //$ranking++;
                $comparison = $average->average;
            }
            elseif($average->average == 0){
                $ranking = 0;
            } 
            
            
            
            if ($check->isEmpty()) { 
                $rank = new \App\Ranking();
            }else{
                $rank = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$schoolyear->schoolyear)->first();
            }
            
            if($check->isEmpty()){
                $rank->idno = $average->idno;
            }
                switch ($quarter){
                  case 1;
                        $rank->tech_1 = $ranking;
                break;
                    case 2;
                        $rank->tech_2 = $ranking;
                    break;                
                   case 3;
                        $rank->tech_3 = $ranking;
                    break;
                    case 4;
                        $rank->tech_4 = $ranking;
                   break;            
                   
                }
            $rank->schoolyear =   $schoolyear->schoolyear;  
            $rank->save();
            $nextrank++;
        }
        
        return $level;        
    }
    
    function weightedRank($quarter,$schoolyear,$strand,$level,$section){
            switch ($quarter){
                    case 1;
                        $averages = DB::Select("SELECT grades.idno,weighted,ROUND( SUM( first_grading *(weighted/100))  , 0 ) AS average FROM `grades` join statuses on statuses.idno = grades.idno WHERE  subjecttype =1 AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear' AND statuses.strand = '$strand' GROUP BY idno ORDER BY `average` DESC");
                    break;
                    case 2;
                        $averages = DB::Select("SELECT grades.idno,weighted,ROUND( SUM( second_grading *(weighted/100))  , 0 ) AS average FROM `grades` join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear' AND statuses.strand = '$strand' GROUP BY idno ORDER BY `average` DESC");
                    break;                
                    case 3;
                        $averages = DB::Select("SELECT grades.idno,weighted,ROUND( SUM( third_grading *(weighted/100))  , 0 ) AS average FROM `grades` join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear' AND statuses.strand = '$strand' GROUP BY idno ORDER BY `average` DESC");
                    break;
                    case 4;
                        $averages = DB::Select("SELECT grades.idno,weighted,ROUND( SUM( fourth_grading *(weighted/100))  , 0 ) AS average FROM `grades` join statuses on statuses.idno = grades.idno WHERE subjecttype =1 AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$schoolyear' AND statuses.strand = '$strand' GROUP BY idno ORDER BY `average` DESC");
                    break;                
                }    


        return $averages;
        
    }    
    
    function getstrand($level){
        if(Request::ajax()){
            $strands = DB::Select("select distinct strand from ctr_sections where level = '$level'");
            
            $data = "<div class=\"form form-group\"><label for=\"strand\">Select Shop/Strand</label>";
            $data=$data. "<Select name =\"strand\" id=\"strand\" class=\"form form-control\" onchange=\"getstrandall(this.value)\" >";
            $data=$data. "<option>--Select--</option>";
                foreach($strands as $strand){
                    $data = $data . "<option value=\"". $strand->strand . "\">" . $strand->strand . "</option>";       
                }
            $data = $data . "</select></div>"; 
            return $data;
        //    return data;
        }
    }  
    
    function getsection($level){
        if(Request::ajax()){
            $strand = Input::get("strand");
                $sections = DB::Select("select  distinct section from statuses where level = '$level' and section != '' and strand = '$strand'");

               $data = "";
               $data = $data . "<div class=\"col-md-6\"><label for=\"section\">Select Section</label><select id=\"section\" onchange=\"genSubj()\" class=\"form form-control\">";
             $data = $data . "<option>--Select--</option>";
               foreach($sections as $section){
                  $data = $data . "<option value= '". $section->section ."'>" .$section->section . "</option>";  
                }
               $data = $data."</select></div>";
            return $data;   
            //return $level;
        }
    }  

    function getsubjects($level){
        if(Request::ajax()){
            $strand = Input::get("strand");
            if(empty($strand)){
                
                $subjects = DB::Select("select  distinct subjectname as subject,subjectcode from ctr_subjects where level = '$level' and subjecttype IN (0,1,5,6) ORDER BY subjecttype asc, sortto asc");
            }else{
             
                if($level == "Grade 11" || $level == "Grade 12" ){
                       $sem = Input::get("sem");
                    $subjects = DB::Select("select  distinct subjectname as subject,subjectcode from ctr_subjects where level = '$level' and subjecttype IN (0,1,5,6) and strand = '$strand' and semester = $sem  ORDER BY subjecttype asc, sortto asc");
                }else{
                    $subjects = DB::Select("select  distinct subjectname as subject,subjectcode from ctr_subjects where level = '$level' and subjecttype IN (0,1,5,6) ORDER BY subjecttype asc, sortto asc");
                }
                
            }

            
               $data = "";
               $data = $data . "<div class=\"col-md-6\"><label for=\"section\">Select Subject</label><select id=\"subject\" onchange=\"showbtn()\" class=\"form form-control\">";
             $data = $data . "<option>--Select--</option>";
             $data = $data . "<option value= 'All'>All</option>";  
               foreach($subjects as $subject){
                  $data = $data . "<option value= '". $subject->subjectcode ."'>" .$subject->subject . "</option>";  
                }
                
               $data = $data."</select></div>";
            return $data;   
            //return $level;
        }
    }      
    
    function getsection1($level){
        if(Request::ajax()){  
          $sections = DB::Select("select  distinct section from ctr_sections where level = '$level'");
               $data = "";
               $data = $data . "<label for=\"section\">Section</label><select id=\"section\" onchange=\"showqtr()\" class=\"form form-control\">";
             $data = $data . "<option disabled hidden>--Select--</option>";
               foreach($sections as $section){
                  $data = $data . "<option value= '". $section->section ."'>" .$section->section . "</option>";  
                }
               $data = $data."</select>";
            return $data;   
            //return $level;
        }
    }
    
    function getadviser(){
        $lvl = Input::get('level');
        $sec = Input::get('section');
        
        $dept=Input::get('department');
        //$strand = '';
        if( is_null(Input::get('strand'))){
            $adviser = \App\CtrSection::where('level',$lvl)->where('section',$sec)->first();
        }else{
            $adviser = \App\CtrSection::where('level',$lvl)->where('strand',Input::get('strand'))->where('section',$sec)->first();
        }        
        
        
        
        if(isset($adviser->adviser)){
        $data = $adviser->adviser;
        }
        else{
            $data = "NONE";
        }
        return $data;
    }
    
    function getdos(){
        $qtr = Input::get('quarter');
        switch ($qtr){
            case 1;
                //$averages = DB::Select("SELECT idno,weighted, ROUND( SUM( first_grading ) / count( idno ) , 0 ) AS average FROM `grades` WHERE subjecttype =0 AND level = '$level' AND section LIKE '$section' AND schoolyear = '$schoolyear->schoolyear' GROUP BY idno ORDER BY `average` DESC");
                $averages = DB::Select("SELECT sum(first_grading) as grade FROM `grades` where subjectcode IN ('DAYP','DAYA') group by idno ORDER BY grade  DESC");
            break;
            case 2;
                $averages = DB::Select("SELECT sum(second_grading) as grade FROM `grades` where subjectcode IN ('DAYP','DAYA') group by idno ORDER BY grade  DESC");
            break;                
           case 3;
                $averages = DB::Select("SELECT Nov +Dece as grade FROM `ctr_attendances` where id =1");
            break;
            case 4;
                $averages = DB::Select("SELECT sum(fourth_grading) as grade FROM `grades` where subjectcode IN ('DAYP','DAYA') group by idno ORDER BY grade  DESC");
           break;                
        }
        
        $data = number_format($averages[0]->grade,1);
        
        return $data;
    }
    
    function viewallrank($level){
        if(Request::ajax()){
            $quarter = Input::get('quarter');
            $data = "";
            $sortby = "oa_acad_".$quarter;
            $sy = '2016';
            
            if($level == "Grade 11" |$level == "Grade 12"){
                $strand = Input::get('strand');
                $students = DB::Select("select statuses.department as department,lastname,firstname,middlename,extensionname,section, users.idno as idno,oa_acad_$quarter as acad_rank,oa_tech_$quarter  as tech_rank from users join statuses on users.idno = statuses.idno join rankings on rankings.idno =users.idno  where level = '$level' and rankings.schoolyear = '2016' and statuses.schoolyear = '2016' and strand ='$strand' and statuses.status IN (2,3) order by $sortby ASC");
                $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->where('strand',$strand)->orderBy('sortto','ASC')->get();                
            }else{
                $students = DB::Select("select statuses.department as department,lastname,firstname,middlename,extensionname,section, users.idno as idno,oa_acad_$quarter as acad_rank,oa_tech_$quarter  as tech_rank from users join statuses on users.idno = statuses.idno join rankings on rankings.idno =users.idno  where level = '$level' and rankings.schoolyear = '2016' and statuses.schoolyear = '2016' and statuses.status IN (2,3) order by $sortby ASC");
                $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
            }

            $data = $data."<table class='overall' style='text-align:center' border='1' cellspacing='0' cellpadding='1' width='100%'><thead><td>Section</td>"
                    . "<td>Name</td>";
            foreach($subjects as $subject){
                if($subject->subjecttype == 0){
                $data = $data."<td>".$subject->subjectname."</td>";
                }
                if($subject->subjecttype == 5 |$subject->subjecttype == 6){
                $data = $data."<td>".$subject->subjectname."</td>";
                }                
            }
            $data = $data."<td>General Average</td><td>Overall Ranking</td>";
            foreach($subjects as $subject){
                if($subject->subjecttype == 1){
                $data = $data."<td>".$subject->subjectname."</td>";
                }
            }  
            if($level == "Grade 7" | $level == "Grade 8" | $level == "Grade 9" | $level == "Grade 10"){
            $data = $data."<td>General Average</td><td>Overall Ranking</td>";
            }
            $data = $data."<thead>";
            foreach($students as $student){
                $data = $data."<tr>";
                $data = $data."<td style='text-align:left'>".strrchr($student->section," ")."</td>"
                        . "<td style='text-align:left'>".$student->lastname.", ".$student->firstname." ".$student->middlename." ".$student->extensionname."</td>";
                switch ($quarter){
                        case 1;
                            $grades = \App\Grade::select('subjecttype','first_grading as grade') ->where('idno',$student->idno)->where('schoolyear',$sy)->where('isdisplaycard',1)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                        break;
                        case 2;
                            $grades = \App\Grade::select('subjecttype','second_grading as grade')->where('idno',$student->idno)->where('schoolyear',$sy)->where('isdisplaycard',1)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                        break;                
                        case 3;
                            $grades = \App\Grade::select('subjecttype','third_grading as grade') ->where('idno',$student->idno)->where('schoolyear',$sy)->where('isdisplaycard',1)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                        break;
                        case 4;
                            $grades = \App\Grade::select('subjecttype','fourth_grading as grade')->where('idno',$student->idno)->where('schoolyear',$sy)->where('isdisplaycard',1)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
                        break;                
                    }
                    
                    foreach($grades as $grade){
                        if($grade->subjecttype == 0){
                                $data = $data."<td>".round($grade->grade,0)."</td>";
                        }
                        if($grade->subjecttype == 5 | $grade->subjecttype == 6){
                                $data = $data."<td>".round($grade->grade,0)."</td>";
                        }                        
                    }
                    if($student->department == "Elementary" |$student->department == "Kindergarten"){
                        $data = $data."<td>".number_format(round($this->calcGrade(0,$quarter,$student->idno,$sy),2),2)."</td>";
                    }
                    if($student->department == "Junior High School"){
                        $data = $data."<td>".round($this->calcGrade(0,$quarter,$student->idno,$sy),0)."</td>";
                    }
                    if($student->department == "Senior High School"){
                        $data = $data."<td>".round($this->calcSeniorGrade($quarter,$student->idno,$sy),0)."</td>";
                    }
                    $data = $data."<td>".$student->acad_rank."</td>";
                    
                    foreach($grades as $grade){
                        if($grade->subjecttype == 1){
                                $data = $data."<td>".round($grade->grade,0)."</td>";
                        }
                    }
                    if($student->department == "Junior High School"){
                        $data = $data."<td>".round($this->calcGrade(1,$quarter,$student->idno,$sy),0)."</td>";
                        $data = $data."<td>".$student->tech_rank."</td>";   
                    }  
                    
                $data = $data."</tr>";
            }            
            $data = $data."</table>";
            
            
            return $data;
        }
    }
    
    function searchStudtvet($search){
     $students = DB::Select("select lastname,firstname,middlename,extensionname,gender,users.idno,statuses.status from users join statuses on statuses.idno = users.idno "
                            . "where statuses.department = 'TVET' "
                            . "AND (lastname LIKE '$search%' OR firstname LIKE '$search%' OR users.idno LIKE '$search%')");
     $data = "";
     $data = $data."<table class='table table-striped'><thead>";
     $data = $data."<tr><th>Student Number</th><th>Student Name</th><th>Gender</th><th>Plan</th></tr>";
     $data = $data."</thead>";
     $data = $data."<tbody>";
               
    foreach($students as $student){
        $data =$data."<tr><td>".$student->idno."</td><td>".$student->lastname.", ".$student->firstname." ".$student->middlename." ".$student->extensionname.
               "</td><td>".$student->gender."</td><td>";
        if($student->status == 2){
            $data =$data."<a href='/planset/".$student->idno."'>view</a>";
        }else{
            $data =$data."Currently not Enrolled";   
        }
        $data =$data."</td></tr>";
    }
    $data =$data."</tbody>";
    $data =$data."</table>";

    return $data;
 }
 
    function changeTotal($total){
     $student = Input::get('students');
     $batch = Input::get('batch');
     
     $change = \App\TvetSubsidy::where('idno',$student)->where('batch',$batch)->first();
     $change->amount = $total;
     $change->save();
     
     return $change->idno;
 }
 
    function changeSubsidy($total){
     $student = Input::get('students');
     $batch = Input::get('batch');
    
     $change = \App\TvetSubsidy::where('idno',$student)->where('batch',$batch)->first();
     $change->subsidy = $total;
     $change->save();
     
     return $change->idno;
 }
 
    function changeSponsor($total){
     $student = Input::get('students');
     $batch = Input::get('batch');
     
     $change = \App\TvetSubsidy::where('idno',$student)->where('batch',$batch)->first();
     $change->sponsor = $total;
     $change->save();
     
     return $change->idno;
 }
 
    function gettvetstudentlist($batch,$strand){
        if(Request::ajax()){


                $studentnames = DB::Select("select statuses.id, statuses.idno, users.lastname, "
                    . "users.firstname, users.middlename, statuses.section  from statuses, users where statuses.idno = "
                    . "users.idno and statuses.period = '$batch' and statuses.course = '" .$strand."'  and statuses.status = '2' order by users.lastname, users.firstname, users.middlename");


            $data = "";
            $data = $data . "<table class=\"table table-stripped\"><tr><td>ID No</td><td>Name</td><td>Section</td></tr>";
                foreach($studentnames as $studentname){
                    $data = $data . "<tr><td>".$studentname->idno."</td><td><span style=\"cursor:pointer\"onclick=\"setsection('" . $studentname->id . "')\">".$studentname->lastname . ", " . $studentname->firstname . " " .$studentname->middlename . "</span></td><td>" . $studentname->section . "</td></tr>"; 
                }
            $data = $data."</table>";

            return $data;
        }        
    }
    
    function gettvetsection($batch){
            if(Request::ajax()){
                $course = Input::get("course");
                $sections = DB::Select("select  * from ctr_sections where level = '$batch' and course = '$course'");
                   $data = "";
                   $data = $data . "<div class=\"col-md-6\"><label for=\"section\">Select Section</label><select id=\"section\" onchange=\"callsection()\" class=\"form form-control\">";
                 $data = $data . "<option hidden>--Select--</option>";
                   foreach($sections as $section){
                      $data = $data . "<option value= '". $section->section ."'>" .$section->section . "</option>";  
                    }
                   $data = $data."</select></div>";
                return $data;   
                //return "roy";
            }
        }
        
    function gettvetsectionlist($batch,$section){
            if(Request::ajax()){
                 $ad = \App\CtrSection::where('level',$batch)->where('section',$section)->where('course',Input::get("course"))->first();
                 $adviser = $ad->adviser;
                     if ($batch === "87"){
                        $studbatch = "1st Batch";
                    }else{
                        $studbatch = $batch;
                    }
                $studentnames = DB::Select("select statuses.id, statuses.idno, users.lastname, "
                        . "users.firstname, users.middlename, statuses.section from statuses, users where statuses.idno = "
                        . "users.idno and statuses.period = '".$studbatch."'  AND statuses.section = '$section' and course = '" . Input::get("course") . "' "
                        . "order by users.gender, users.lastname, users.firstname, users.middlename");
                $cn=1;
                $data = "<div class=\"col-md-6\"><label for=\"adviser\">Adviser</label><input type=\"text\" id=\"adviser\" class=\"form form-control\" value=\"" . $adviser . "\" onkeyup = \"updateadviser(this.value,'" . $ad->id . "')\"></div>";
                $data = $data . "<table class=\"table table-stripped\"><tr><td>ID No</td><td>CN</td><td>Name</td><td>Section</td></tr>";
                    foreach($studentnames as $studentname){
                        $data = $data . "<tr><td>".$studentname->idno."</td><td>" . $cn++ . "</td><td><span style=\"cursor:pointer\" onclick=\"rmsection('" . $studentname->id . "')\">".$studentname->lastname . ", " . $studentname->firstname . " " .$studentname->middlename . "</span></td><td>" . $studentname->section . "</td></tr>"; 
                    }
                $data = $data."</table>";
                $data = $data . "<a href = \"". url('/printsection', array($batch,$section,Input::get('course')))."\" class =\"btn btn-primary\"> Print Section</a>";
                return $data;
                
            }
        }


    function gettvetledgersection($batch,$course){
            if(Request::ajax()){
                
                $sections = DB::Select("select  * from ctr_sections where level = '$batch' and course = '$course'");
                $data = "";
                $data = $data . "<option hidden>--Select--</option>";
                foreach($sections as $section){
                    $data = $data . "<option value= '". $section->section ."'>" .$section->section . "</option>";  

                }
                return $data;   
                
            }
    }
    
    function dropStudent($idno){
        $sy = \App\CtrRefSchoolyear::first(); 
        
        $status = \App\Status::where('idno',$idno)->where('schoolyear',$sy->schoolyear)->first();
        $status->status = 3;
        $status->dropdate = date("Y-m-d");
        $status->save();
        
        return "Dropped";
    }
    
    function getsubsidy($account){
        $subsidies = \App\CtrOtherPayment::where('accounttype',$account)->get();
        $data = '';
        $data =$data.'<option disable hidden>--Select--</option>';
        foreach ($subsidies as $subsidy){
            $data=$data . '<option value= "'. $subsidy->particular.'">'.$subsidy->particular.'</option>';
        }
        
        return $data;
    }
    
    function getlevel(){
        if(Request::ajax()){
            $levels = \App\CtrLevel::get();
            $data = '<label>Level</label>';
            $data =$data.'<select name="level" id="level" class="form form-control" onchange="setsection(this.value)">';
                foreach($levels as $level){
                    $data =$data.'<option disable hidden>--Select--</option>';
                    $data =$data.'<option value= "'.$level->level.'">'.$level->level.'</option>';
                }
            $data =$data.'</select>';

            return $data;
        }
    }
    
    function studentselect(){
        if(Request::ajax()){
            $level = Input::get('level');
            $section = Input::get('section');
            //$section = "All";
            $sy = \App\CtrRefSchoolyear::first();
            
            if($section == "All"){
                //$students = DB::Select("Select statuses.idno,lastname,firstname,middlename,extensionname from statuses join users on users.idno = statuses.idno join ctr_sections on ctr_sections.section = statuses.section where statuses.status = 2 and schoolyear = $sy->schoolyear and level = '$level' order by ctr_sections.id ASC");
                $students = DB::Select("Select distinct statuses.idno,lastname,firstname,middlename,extensionname,statuses.section from statuses join users on users.idno = statuses.idno join ctr_sections on ctr_sections.section = statuses.section where statuses.status = 2 and statuses.schoolyear = $sy->schoolyear and statuses.level = '$level' order by lastname,firstname,middlename,extensionname");
            }else{
                //$students = DB::Select("Select statuses.idno,lastname,firstname,middlename,extensionname from statuses join users on users.idno = statuses.idno join ctr_sections on ctr_sections.section = statuses.section where statuses.status = 2 and schoolyear = $sy->schoolyear and level = '$level' and section = '$section' order by ctr_sections.id ASC");
                $students = DB::Select("Select distinct statuses.idno,lastname,firstname,middlename,extensionname,statuses.section from statuses join users on users.idno = statuses.idno join ctr_sections on ctr_sections.section = statuses.section where statuses.status = 2 and statuses.schoolyear = $sy->schoolyear and statuses.level = '$level' and statuses.section = '$section' order by lastname,firstname,middlename,extensionname");
            }
            
            $data = '<table class = "table table-responsive"><thead><td>Student No</td><td>Name</td><td></td></thead>';
            foreach($students as $student){
                $data = $data.'<tr>';
                $data = $data.'<td>'.$student->idno.'</td>';
                $data = $data.'<td>'.$student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->extensionname.'</td>';
                $data = $data.'<td><input type="checkbox" name="idnumber[]" value="'.$student->idno.'" checked="checked"></td>';
                $data = $data.'</tr>';
            }
            $data = $data.'</table>';

            return $data;       
            
        }      

    }
    
    function getfinal(){
        if(Request::ajax()){
            $section = Input::get('section');
            //$section = "Saint Louis Versiglia";
            $level = Input::get('level');
            //$level = "Grade 7";
            $strand = Input::get('strand');
            //$strand = '';
            $department = Input::get('department');
            //$department = "Junior High School";
            $sy = \App\ctrSchoolYear::first();
            
            if($strand == ''){
                $students = DB::Select("Select * from users left join statuses on users.idno = statuses.idno left join rankings on rankings.idno = statuses.idno and rankings.schoolyear = statuses.schoolyear where statuses.status IN (2,3) and statuses.level = '$level' and statuses.section = '$section' order by class_no ASC");
            }else{
                $students = DB::Select("Select * from users left join statuses on users.idno = statuses.idno left join rankings on rankings.idno = statuses.idno and rankings.schoolyear = statuses.schoolyear where statuses.status IN (2,3) and statuses.level = '$level' and statuses.section = '$section' AND statuses.strand = '$strand' order by class_no ASC");
            }
            switch($department){
                case 'Kindergarten';
                    $report = $this->elemFinalReport($students,$level,$sy);
                break;
                case 'Elementary';
                    $report = $this->elemFinalReport($students,$level,$sy);
                break;
                case 'Junior High School';
                    $report = $this->HSFinalReport($students,$level,$sy);
                break;
                case 'Senior High School';
                    $sem = Input::get('sem');
                    $report = $this->SHSFinalReport($students,$sem,$level,$sy,$strand);
                break;
                default;
                    $report = "No students found.";
                break;
            }
            return $report;
        }
        
    }
    
    function elemFinalReport($students,$level,$sy){
        $report="";
        $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
        
        $report = $report . "<table style='text-align:center;' border='1' width='2000px'>";
        $report = $report . "<tr><td rowspan='2'>CN</td><td rowspan='2' >Student Name</td>";
        foreach($subjects as $subject){
            if($subject->subjecttype == 0){
                $report = $report . "<td colspan = '4'>".$subject->subjectcode."</td>";
            }
        }
        $report = $report . "<td colspan = '4'>ACAD GEN AVE</td><td colspan = '4'>RANK</td>";
        $report = $report . "<td colspan = '4'>GMRC</td>";
        $report = $report . "<td colspan = '4'>DAYP</td><td colspan = '4'>DAYA</td><td colspan = '4'>DAYT</td>";
        $report = $report . "</tr>";
        $report = $report . "<tr>";
        foreach($subjects as $subject){
            if($subject->subjecttype == 0){
                $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
            }
        }
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "</tr>";
            
        foreach($students as $student){
            $report = $report . "<tr>";
            $report = $report . "<td>".$student->class_no."</td><td style='text-align:left'>".$student->lastname.", ".$student->firstname." ".$student->middlename." ".$student->extensionname;
            if($student->status == 3){
                $report = $report . "<span style='float:right;color:red;'>DROPPED</span>";
            }
            $report = $report . "</td>";
            foreach($subjects as $subject){
                if($subject->subjecttype == 0){
                    $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->first();
                    $report = $report . "<td>".$this->blankgrade($grade->first_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->second_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->third_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->fourth_grading)."</td>";
                    
                }
            }
            
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(0,1,$student->idno,$sy->schoolyear))."</td>";
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(0,2,$student->idno,$sy->schoolyear))."</td>";
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(0,3,$student->idno,$sy->schoolyear))."</td>";
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(0,4,$student->idno,$sy->schoolyear))."</td>";
            
            $report = $report . "<td>".$this->blankgrade($student->acad_1)."</td>";
            $report = $report . "<td>".$this->blankgrade($student->acad_2)."</td>";
            $report = $report . "<td>".$this->blankgrade($student->acad_3)."</td>";
            $report = $report . "<td>".$this->blankgrade($student->acad_4)."</td>";
            
            //CONDUCT            
            $conduct1 = 0;
            $conduct2 = 0;
            $conduct3 = 0;
            $conduct4 = 0;
                        
            foreach($subjects as $subject){
                if($subject->subjecttype == 3){
                    $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->first();
                        $conduct1 = $conduct1+$grade->first_grading;
                        $conduct2 = $conduct2+$grade->second_grading;
                        $conduct3 = $conduct3+$grade->third_grading;
                        $conduct4 = $conduct4+$grade->fourth_grading;
                }
            }
            
            
            
            $report = $report . "<td>".$this->blankgrade($conduct1)."</td>";
            $report = $report . "<td>".$this->blankgrade($conduct2)."</td>";
            $report = $report . "<td>".$this->blankgrade($conduct3)."</td>";
            $report = $report . "<td>".$this->blankgrade($conduct4)."</td>";
            
            //ATTENDANCE
            $dayp = array();
            $dayt = array();
            $daya = array();
            $attendance = array();
            for($i=1; $i < 5 ;$i++){
                $attendance  = $this->getAttendance($i,$student->idno,$sy);
                $dayp [] = $attendance[1];
                $dayt [] = $attendance[0];
                $daya [] = $attendance[2];
            }
            $qtr = 1;
            foreach($dayp as $dayp){
                $report = $report . "<td>".$this->blankattend($dayp,$qtr)."</td>";
                $qtr++;
            }
            $qtr = 1;
            foreach($dayt as $dayt){
                $report = $report . "<td>".$this->blankattend($dayt,$qtr)."</td>";
                $qtr++;
            }
            $qtr = 1;
            foreach($daya as $daya){
                $report = $report . "<td>".$this->blankattend($daya,$qtr)."</td>";
                $qtr++;
            }
            $report = $report . "</tr>";
        }
        $report = $report . "</table>";
        return $report;
    }
    
    function HSFinalReport($students,$level,$sy){
        $report="";
        $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
        
        $report = $report . "<table width='2500px' style='text-align:center;' border='1'>";
        $report = $report . "<tr><td rowspan='2'>CN</td><td rowspan='2' >Student Name</td>";
        foreach($subjects as $subject){
            if($subject->subjecttype == 0){
                $report = $report . "<td colspan = '4'>".$subject->subjectcode."</td>";
            }
        }
        $report = $report . "<td colspan = '4'>ACAD GEN AVE</td><td colspan = '4'>RANK</td>";
        foreach($subjects as $subject){
            if($subject->subjecttype == 1){
                $report = $report . "<td colspan = '4'>".$subject->subjectcode."</td>";
            }
        }
        $report = $report . "<td colspan = '4'>TECH GEN AVE</td><td colspan = '4'>RANK</td>";
        $report = $report . "<td colspan = '4'>GMRC</td>";
        $report = $report . "<td colspan = '4'>DAYP</td><td colspan = '4'>DAYA</td><td colspan = '4'>DAYT</td>";
        $report = $report . "</tr>";
        $report = $report . "<tr>";
        foreach($subjects as $subject){
            if($subject->subjecttype == 0){
                $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
            }
        }
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        foreach($subjects as $subject){
            if($subject->subjecttype == 1){
                $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
            }
        }  
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";        
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "<td>1st</td><td>2nd</td><td>3rd</td><td>4th</td>";
        $report = $report . "</tr>";
            
        foreach($students as $student){
            $report = $report . "<tr>";
            $report = $report . "<td>".$student->class_no."</td><td style='text-align:left'>".$student->lastname.", ".$student->firstname." ".$student->middlename." ".$student->extensionname;
            if($student->status == 3){
                $report = $report . "<span style='float:right;color:red;'>DROPPED</span>";
            }
            $report = $report . "</td>";
            foreach($subjects as $subject){
                if($subject->subjecttype == 0){
                    $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->first();
                    $report = $report . "<td>".$this->blankgrade($grade->first_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->second_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->third_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->fourth_grading)."</td>";
                    
                }
            }
            
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(0,1,$student->idno,$sy->schoolyear))."</td>";
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(0,2,$student->idno,$sy->schoolyear))."</td>";
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(0,3,$student->idno,$sy->schoolyear))."</td>";
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(0,4,$student->idno,$sy->schoolyear))."</td>";
            
            $report = $report . "<td>".$this->blankgrade($student->acad_1)."</td>";
            $report = $report . "<td>".$this->blankgrade($student->acad_2)."</td>";
            $report = $report . "<td>".$this->blankgrade($student->acad_3)."</td>";
            $report = $report . "<td>".$this->blankgrade($student->acad_4)."</td>";
            
            foreach($subjects as $subject){
                if($subject->subjecttype == 1){
                    $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->first();
                    $report = $report . "<td>".$this->blankgrade($grade->first_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->second_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->third_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->fourth_grading)."</td>";
                    
                }
            }
            
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(1,1,$student->idno,$sy->schoolyear))."</td>";
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(1,2,$student->idno,$sy->schoolyear))."</td>";
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(1,3,$student->idno,$sy->schoolyear))."</td>";
            $report = $report . "<td>".$this->blankgrade($this->calcGrade(1,4,$student->idno,$sy->schoolyear))."</td>";
            
            $report = $report . "<td>".$this->blankgrade($student->tech_1)."</td>";
            $report = $report . "<td>".$this->blankgrade($student->tech_2)."</td>";
            $report = $report . "<td>".$this->blankgrade($student->tech_3)."</td>";
            $report = $report . "<td>".$this->blankgrade($student->tech_4)."</td>";            
            
            //CONDUCT            
            $conduct1 = 0;
            $conduct2 = 0;
            $conduct3 = 0;
            $conduct4 = 0;
                        
            foreach($subjects as $subject){
                if($subject->subjecttype == 3){
                    $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->first();
                        $conduct1 = $conduct1+$grade->first_grading;
                        $conduct2 = $conduct2+$grade->second_grading;
                        $conduct3 = $conduct3+$grade->third_grading;
                        $conduct4 = $conduct4+$grade->fourth_grading;
                }
            }
            
            
            
            $report = $report . "<td>".$this->blankgrade($conduct1)."</td>";
            $report = $report . "<td>".$this->blankgrade($conduct2)."</td>";
            $report = $report . "<td>".$this->blankgrade($conduct3)."</td>";
            $report = $report . "<td>".$this->blankgrade($conduct4)."</td>";
            
            //ATTENDANCE
            $dayp = array();
            $dayt = array();
            $daya = array();
            $attendance = array();
            for($i=1; $i < 5 ;$i++){
                $attendance  = $this->getAttendance($i,$student->idno,$sy);
                $dayp [] = $attendance[1];
                $dayt [] = $attendance[0];
                $daya [] = $attendance[2];
            }
            $qtr = 1;
            foreach($dayp as $dayp){
                $report = $report . "<td>".$this->blankattend($dayp,$qtr)."</td>";
                $qtr++;
            }
            $qtr = 1;
            foreach($dayt as $dayt){
                $report = $report . "<td>".$this->blankattend($dayt,$qtr)."</td>";
                $qtr++;
            }
            $qtr = 1;
            foreach($daya as $daya){
                $report = $report . "<td>".$this->blankattend($daya,$qtr)."</td>";
                $qtr++;
            }
            $report = $report . "</tr>";
        }
        
        $report = $report . "</table>";
        return $report;
    }
    
    function SHSFinalReport($students,$sem,$level,$sy,$strand){
        $report="";
        $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->whereIn('semester',array($sem,0))->where('strand',$strand)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
        
        $report = $report . "<table width='4000px;' style='text-align:center;' border='1'>";
        $report = $report . "<tr><td>CN</td><td>Student Name</td>";
        foreach($subjects as $subject){
            if($subject->subjecttype == 5){
                $report = $report . "<td style='background-color:#fef2cb;'>".$subject->subjectcode."1</td>";
                $report = $report . "<td style='background-color:#deeaf6;'>".$subject->subjectcode."2</td>";
            }
        }
        foreach($subjects as $subject){
            if($subject->subjecttype == 6){
                $report = $report . "<td style='background-color:#fef2cb;'>".$subject->subjectcode."1</td>";
                $report = $report . "<td style='background-color:#deeaf6;'>".$subject->subjectcode."2</td>";
            }
        }        
        $report = $report . "<td style='background-color:#fef2cb;'>ACAD GEN AVE</td><td style='background-color:#deeaf6;'>ACAD GEN AVE</td><td style='background-color:#f7caac;'>ACAD GEN AVE</td>";
        $report = $report . "<td style='background-color:#fef2cb;'>RANKING 1</td><td style='background-color:#deeaf6;'>RANKING 2</td><td style='background-color:#f7caac;'>FINAL RANKING</td>";
        $report = $report . "<td style='background-color:#fef2cb;'>GMRC 1</td><td style='background-color:#deeaf6;'>GMRC 2</td><td style='background-color:#f7caac;'>FINAL GMRC</td>";
        $report = $report . "<td style='background-color:#fef2cb;'>DAYP 1</td><td style='background-color:#deeaf6;'>DAYP 2</td><td style='background-color:#f7caac;'>TOTAL DAYP</td>"
                . "<td style='background-color:#fef2cb;'>DAYA 1</td><td style='background-color:#deeaf6;'>DAYA 2</td><td style='background-color:#f7caac;'>TOTAL DAYA</td>"
                . "<td style='background-color:#fef2cb;'>DAYT 1</td><td style='background-color:#deeaf6;'>DAYT 2</td><td style='background-color:#f7caac;'>TOTAL DAYT</td>";
        $report = $report . "</tr>";
         
        foreach($students as $student){
            $report = $report . "<tr>";
            $report = $report . "<td>".$student->class_no."</td><td style='text-align:left'>".$student->lastname.", ".$student->firstname." ".$student->middlename." ".$student->extensionname;
            
            if($student->status == 3){
                $report = $report . "<span style='float:right;color:red;'>DROPPED</span>";
            }
            $report = $report . "</td>";
            foreach($subjects as $subject){
            if($sem ==1){
                if($subject->subjecttype == 5){
                    $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->orderBy('sortto','ASC')->first();
                    $report = $report . "<td>".$this->blankgrade($grade->first_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->second_grading)."</td>";                    
                }
                if($subject->subjecttype == 6){
                    $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->orderBy('sortto','ASC')->first();
                    $report = $report . "<td>".$this->blankgrade($grade->first_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->second_grading)."</td>";                    
                }
            }else{
                if($subject->subjecttype == 5){
                    $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->orderBy('sortto','ASC')->first();
                    $report = $report . "<td>".$this->blankgrade($grade->third_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->fourth_grading)."</td>";                    
                }
                if($subject->subjecttype == 6){
                    $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->orderBy('sortto','ASC')->first();
                    $report = $report . "<td>".$this->blankgrade($grade->third_grading)."</td>";
                    $report = $report . "<td>".$this->blankgrade($grade->fourth_grading)."</td>";                    
                } 
            }
            }
            if($sem ==1){
                $first = $this->calcSeniorGrade(1,$student->idno,$sy->schoolyear);
                $second = $this->calcSeniorGrade(2,$student->idno,$sy->schoolyear);
                $total = ($first+$second)/2;                
            }elseif($sem ==2){
                $first = $this->calcSeniorGrade(3,$student->idno,$sy->schoolyear);
                $second = $this->calcSeniorGrade(4,$student->idno,$sy->schoolyear);
                $total = ($first+$second)/2;                                
            }

            
            $report = $report . "<td>".round($first,0)."</td>";
            $report = $report . "<td>".round($second,0)."</td>";
            $report = $report . "<td>".round($total,0)."</td>";
            
            if($sem ==1){
                $report = $report . "<td>".$this->blankgrade($student->acad_1)."</td>";
                $report = $report . "<td>".$this->blankgrade($student->acad_2)."</td>";
                $report = $report . "<td>".$this->blankgrade($student->acad_final)."</td>";
            }elseif($sem==2){
                $report = $report . "<td>".$this->blankgrade($student->acad_3)."</td>";
                $report = $report . "<td>".$this->blankgrade($student->acad_4)."</td>";
                $report = $report . "<td>".$this->blankgrade($student->acad_final)."</td>";
            }

            
            //$subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->where('strand',$strand)->orderBy('subjecttype','ASC')->orderBy('sortto','ASC')->get();
            
            //CONDUCT            
            $conduct1 = 0;
            $conduct2 = 0;   
            if($sem ==1){
                foreach($subjects as $subject){
                    if($subject->subjecttype == 3){
                        $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->orderBy('sortto','ASC')->first();
                            $conduct1 = $conduct1+$grade->first_grading;
                            $conduct2 = $conduct2+$grade->second_grading;
                    }
                }
            }else{
                foreach($subjects as $subject){
                    if($subject->subjecttype == 3){
                        $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear',$sy->schoolyear)->orderBy('sortto','ASC')->first();
                            $conduct1 = $conduct1+$grade->third_grading;
                            $conduct2 = $conduct2+$grade->fourth_grading;
                    }
                }                
            }
            
            $report = $report . "<td>".$this->blankgrade($conduct1)."</td>";
            $report = $report . "<td>".$this->blankgrade($conduct2)."</td>";
            $report = $report . "<td>".$this->blankgrade(($conduct1+$conduct2)/2)."</td>";
            
            
            //ATTENDANCE
            $dayp = array();
            $dayt = array();
            $daya = array();
            $attendance = array();
            if($sem == 1){
            for($i=1; $i < 3 ;$i++){
                $attendance  = $this->getAttendance($i,$student->idno,$sy);
                $dayp [] = $attendance[1];
                $dayt [] = $attendance[0];
                $daya [] = $attendance[2];
            }
            }else{
            for($i=3; $i < 5 ;$i++){
                $attendance  = $this->getAttendance($i,$student->idno,$sy);
                $dayp [] = $attendance[1];
                $dayt [] = $attendance[0];
                $daya [] = $attendance[2];
            }     
            }
            $qtr = 1;
            $totaldayp=0;
            foreach($dayp as $dayp){
                $report = $report . "<td>".$this->blankattend($dayp,$qtr)."</td>";
                $totaldayp=$totaldayp+$dayp;
                $qtr++;
            }
            $report = $report . "<td>".$totaldayp."</td>";
            
            $qtr = 1;
            $totaldayt=0;
            foreach($dayt as $dayt){
                $report = $report . "<td>".$this->blankattend($dayt,$qtr)."</td>";
                $totaldayt=$totaldayt+$dayt;
                $qtr++;
            }
            $report = $report . "<td>".$totaldayt."</td>";
            
            $qtr = 1;
            $totaldaya=0;
            foreach($daya as $daya){
                $report = $report . "<td>".$this->blankattend($daya,$qtr)."</td>";
                $totaldaya = $totaldaya + $daya;
                $qtr++;
            }
            $report = $report . "<td>".$totaldaya."</td>";
            
            $report = $report . "</tr>";
        }
        
        $report = $report . "</table>";
        return $report;
    }
    
    function blankgrade($grade){
        
        if ($grade == 0 || $grade == '' || $grade == NULL){
            $grade = '';
        }else{
            $grade = round($grade,2);
        }
        
        return $grade;
    }
    
    function blankattend($grade,$qtr){
        $quarter = \App\CtrQuarter::first();
        if ($quarter->qtrperiod < $qtr){
            $grade = '';
        }else{
            $grade = round($grade,2);
        }
        
        return $grade;
    }    
    
    function getAttendance($quarter,$idno,$schoolyear){
        $attend = array();
        switch ($quarter){
                case 1;
                        $month1 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month','JUN')->orderBy('id','DESC')->first();
                        $month2 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month','JUL')->orderBy('id','DESC')->first();
                        $month3 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month','AUG')->orderBy('id','DESC')->first();
                        if(!empty($month1) && !empty($month2) && !empty($month3)){
                            
                            $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                            $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                            $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                        }else{
                            $dayt = 0;
                            $dayp = 0;
                            $daya = 0;
                        }
              
                break;
                case 2;
                        $month1 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"Sept")->orderBy('id','DESC')->first();
                        $month2 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"OCT")->orderBy('id','DESC')->first();
                        $month3 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"AUG")->orderBy('id','DESC')->first();

                        if(!empty($month1) && !empty($month2) && !empty($month3)){
                            $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                            $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                            $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                        }else{
                            $dayt = 0;
                            $dayp = 0;
                            $daya = 0;
                        }
              
                break;                
                case 3;
                        $month1 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"OCT")->orderBy('id','DESC')->first();
                        $month2 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"NOV")->orderBy('id','DESC')->first();
                        $month3 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"DECE")->orderBy('id','DESC')->first();

                        if(!empty($month1) && !empty($month2) && !empty($month3)){
                            $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                            $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                            $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                        }else{
                            $dayt = 0;
                            $dayp = 0;
                            $daya = 0;
                        }
              
                break;
                case 4;
                        $month1 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"JAN")->orderBy('id','DESC')->first();
                        $month2 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"FEB")->orderBy('id','DESC')->first();
                        $month3 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"MAR")->orderBy('id','DESC')->first();

                        if(!empty($month1) && !empty($month2) && !empty($month3)){
                            $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                            $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                            $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                        }else{
                            $dayt = 0;
                            $dayp = 0;
                            $daya = 0;
                        }
              
                break;                
            }
            
            return array($dayt,$dayp,$daya);
    }
}
