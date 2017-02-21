<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;

class GradeController extends Controller
{
    //
    
    public function __construct()
	{
		$this->middleware('auth');
	}

    
    public function viewGrades($idno){
        $student = DB::Select("SELECT *  from users left join statuses on users.idno = statuses.idno left join student_infos on users.idno=student_infos.idno where users.idno =  $idno");
        $matchfield = ["level"=>$student[0]->level,"section"=>$student[0]->section];
        
        $teacher = \App\CtrSection::where($matchfield)->first();

        $match = ["idno"=>$idno,"subjecttype"=>0,"schoolyear"=>2016];
        $academic = \App\Grade::where($match)->orderBy("sortto","ASC")->get();
        

        $match2 = ["idno"=>$idno,"subjecttype"=>3,"schoolyear"=>2016];
        $conduct = \App\Grade::where($match2)->orderBy("sortto","ASC")->get();
        
        $match3 = ["idno"=>$idno,"subjecttype"=>2,"schoolyear"=>2016];
        $attendance = \App\Grade::where($match3)->orderBy("sortto","ASC")->get();        
        
        return view('registrar.gradecard',compact('student','teacher','academic','academicSubjects','conduct','attendance','idno'));
        //return count($conduct) ."-". $conduct;

    }
    
    function reset(){
        $no_student=0;
                    $students = \App\Status::whereIn('status',array(2,3))->where('strand','ABM')->get();
        foreach($students as $student){
            $subjects = \App\CtrSubjects::where('level',$student->level)->where('semester',2)->where('strand','ABM')->get();
                    foreach($subjects as $subject){
                            $newgrade = new \App\Grade;
                            $newgrade->idno = $student->idno;
                            $newgrade->semester = $subject->semester;
                            $newgrade->department = $student->department;
                            $newgrade->level = $student->level;
                            $newgrade->subjecttype = $subject->subjecttype;
                            $newgrade->subjectcode = $subject->subjectcode;
                            $newgrade->subjectname = $subject->subjectname;
                            $newgrade->points = $subject->points;
                            $newgrade->weighted = $subject->weighted;
                            $newgrade->sortto = $subject->sortto;
                            $newgrade->schoolyear = $student->schoolyear;
                            $newgrade->save();
                    }
                    $no_student =$no_student +1;
                    echo "NO Of Student: ".$no_student;
        }
        /*
        $students = \App\Status::where('status',2)->where('department','Kindergarten')->get();
        foreach($students as $student){
            $subjects = \App\CtrCompetence::where('quarter',2)->get();
                    foreach($subjects as $subject){
                            $newgrade = new \App\Competency;
                            $newgrade->idno = $student->idno;
                            $newgrade->subject = $subject->subject;
                            $newgrade->section = $subject->section;
                            $newgrade->competencycode = $subject->competencycode;
                            $newgrade->description = $subject->description;
                            $newgrade->sortto = $subject->sortto;
                            $newgrade->quarter = $subject->quarter;
                            $newgrade->competencycode=$subject->competencycode;
                            $newgrade->schoolyear = $student->schoolyear;
                            $newgrade->save();
                    }
                    
        }        
        
        $students = \App\Grade::distinct()->select('idno')->get();
        foreach($students as $student){
            $subjects = \App\CtrSubjects::where('subjecttype',2)->where('level','Grade 11')->where('strand','ABM')->get();
                    foreach($subjects as $subject){
                            $newgrade = new \App\Attendance;
                            $newgrade->idno = $student->idno;
                            $newgrade->attendancetype = $subject->subjectcode;
                            $newgrade->sortto = $subject->sortto;
                            $newgrade->schoolyear = 2016;
                            $newgrade->save();
                    }
                    
        } */       
    }
    
    function viewSectionGrade9to10($level,$shop,$section){
        $schoolyear = \App\CtrRefSchoolyear::first();
        $collection = array();
        $students = DB::Select("SELECT statuses.department,gender,class_no,strand,users.idno,users.lastname, users.firstname,users.middlename,users.extensionname,student_infos.lrn,gender,birthDate from users left join statuses on users.idno = statuses.idno left join student_infos on users.idno=student_infos.idno where statuses.status IN (2,3) AND level LIKE '$level' AND section LIKE '$section' AND strand LIKE '$shop' ORDER BY statuses.class_no ASC");

        $matchfield = ["level"=>$level,"section"=>$section,"strand"=>$shop];
        $teacher = \App\CtrSection::where($matchfield)->first();        
        
        foreach($students as $student){
                $match = ["idno"=>$student->idno,"subjecttype"=>0,"schoolyear"=>$schoolyear->schoolyear,"isdisplaycard"=>1];
                $academic = \App\Grade::where($match)->orderBy("sortto","ASC")->get();

                $match2 = ["idno"=>$student->idno,"subjecttype"=>3,"schoolyear"=>$schoolyear->schoolyear];
                $conduct = \App\Grade::where($match2)->orderBy("sortto","ASC")->get();

                $match3 = ["idno"=>$student->idno,"schoolyear"=>$schoolyear->schoolyear];
                $attendance = \App\Attendance::where($match3)->orderBy("sortto","ASC")->get();

                $match4 = ["idno"=>$student->idno,"subjecttype"=>1,"schoolyear"=>$schoolyear->schoolyear];
                $technical = \App\Grade::where($match4)->orderBy("sortto","ASC")->get();

                $match5 = ["idno"=>$student->idno,"subjecttype"=>5,"schoolyear"=>$schoolyear->schoolyear];
                $core = \App\Grade::where($match5)->orderBy("sortto","ASC")->get();

                $match6 = ["idno"=>$student->idno,"subjecttype"=>6,"schoolyear"=>$schoolyear->schoolyear];
                $special = \App\Grade::where($match6)->orderBy("sortto","ASC")->get();

                $age_year = date_diff(date_create($student->birthDate), date_create('today'))->y;
                $age_month = date_diff(date_create($student->birthDate), date_create('today'))->m;
                $age= $age_year.".".$age_month;
                $student->age = $age;
                $collection[] = array('info'=>$student,'aca'=>$academic,'con'=>$conduct,'att'=>$attendance,'tech'=>$technical,'core'=>$core,'spec'=>$special);
            }

        return view('registrar.sectiongrade9to10duplex',compact('collection','students','level','section','teacher','schoolyear','shop'));
    }
    
    function viewSectionGrade11to12($level,$shop,$section,$sem){
        $schoolyear = \App\CtrRefSchoolyear::first();
        $collection = array();        
        $students = DB::Select("SELECT statuses.department,gender,class_no,strand,users.idno,users.lastname, users.firstname,users.middlename,users.extensionname,student_infos.lrn,gender,birthDate from users left join statuses on users.idno = statuses.idno left join student_infos on users.idno=student_infos.idno where statuses.status IN (2,3) AND level LIKE '$level' AND section LIKE '$section' AND strand LIKE '$shop' ORDER BY statuses.class_no ASC");

        $matchfield = ["level"=>$level,"section"=>$section,"strand"=>$shop];
        $teacher = \App\CtrSection::where($matchfield)->first();        
        
        foreach($students as $student){
                    $match = ["idno"=>$student->idno,"subjecttype"=>0,"schoolyear"=>$schoolyear->schoolyear,"isdisplaycard"=>1];
                    $academic = \App\Grade::where($match)->orderBy("sortto","ASC")->get();

                    $match2 = ["idno"=>$student->idno,"subjecttype"=>3,"schoolyear"=>$schoolyear->schoolyear];
                    $conduct = \App\Grade::where($match2)->orderBy("sortto","ASC")->get();

                    if($student->department == "Senior High School"){
                    $match3 = ["idno"=>$student->idno,"schoolyear"=>$schoolyear->schoolyear];
                    $attendance = \App\Attendance::where($match3)->orderBy("sortto","ASC")->get();
                    }else{
                    $match3 = ["idno"=>$student->idno,"subjecttype"=>2,"schoolyear"=>$schoolyear->schoolyear];
                    $attendance = \App\Grade::where($match3)->orderBy("sortto","ASC")->get();
                    }
                    
                    $match4 = ["idno"=>$student->idno,"subjecttype"=>1,"schoolyear"=>$schoolyear->schoolyear];
                    $technical = \App\Grade::where($match4)->orderBy("sortto","ASC")->get();
                    
                    $match5 = ["idno"=>$student->idno,"subjecttype"=>5,"schoolyear"=>$schoolyear->schoolyear,"semester"=>$sem];
                    $core = \App\Grade::where($match5)->orderBy("sortto","ASC")->get();
                    
                    $match6 = ["idno"=>$student->idno,"subjecttype"=>6,"schoolyear"=>$schoolyear->schoolyear,"semester"=>$sem];
                    $special = \App\Grade::where($match6)->orderBy("sortto","ASC")->get();
                    
                    $age_year = date_diff(date_create($student->birthDate), date_create('today'))->y;
                    $age_month = date_diff(date_create($student->birthDate), date_create('today'))->m;
                    $age= $age_year.".".$age_month;
                    $student->age = $age;
                    $collection[] = array('info'=>$student,'aca'=>$academic,'con'=>$conduct,'att'=>$attendance,'tech'=>$technical,'core'=>$core,'spec'=>$special);
                    //$collection[] = array('info'=>$student);
                }
            
        return view('registrar.sectiongrade11',compact('collection','students','level','section','teacher','schoolyear','sem'));
    }    
    
    function viewSectionGrade($level,$section){
        $schoolyear = \App\CtrRefSchoolyear::first();
        $collection = array();
        $students = DB::Select("SELECT birthDate,class_no,department,users.idno,users.lastname, users.firstname,users.middlename,users.extensionname,student_infos.lrn,gender,birthDate from users left join statuses on users.idno = statuses.idno left join student_infos on users.idno=student_infos.idno where statuses.status IN (2,3) AND level LIKE '$level' AND section LIKE '$section' ORDER BY statuses.class_no ASC");

        $matchfield = ["level"=>$level,"section"=>$section];
        $teacher = \App\CtrSection::where($matchfield)->first();        
        
        foreach($students as $student){
                    $match = ["idno"=>$student->idno,"subjecttype"=>0,"schoolyear"=>$schoolyear->schoolyear,"isdisplaycard"=>1];
                    $academic = \App\Grade::where($match)->orderBy("sortto","ASC")->get();


                    $match2 = ["idno"=>$student->idno,"subjecttype"=>3,"schoolyear"=>$schoolyear->schoolyear];
                    $conduct = \App\Grade::where($match2)->orderBy("sortto","ASC")->get();

                    $match3 = ["idno"=>$student->idno,"schoolyear"=>$schoolyear->schoolyear];
                    $attendance = \App\Attendance::where($match3)->orderBy("sortto","ASC")->get();

                    $match4 = ["idno"=>$student->idno,"subjecttype"=>1,"schoolyear"=>$schoolyear->schoolyear];
                    $technical = \App\Grade::where($match4)->orderBy("sortto","ASC")->get();

                    $age_year = date_diff(date_create($student->birthDate), date_create('today'))->y;
                    $age_month = date_diff(date_create($student->birthDate), date_create('today'))->m;
                    $age= $age_year.".".$age_month;
                    $student->age = $age;                    
                    
                    $collection[] = array('info'=>$student,'aca'=>$academic,'con'=>$conduct,'att'=>$attendance,'tech'=>$technical);
                }           
                
        if($students[0]->department =="Elementary"){            
            //return view('registrar.sectiongradereport',compact('collection','students','level','section','teacher','schoolyear'));
            return view('registrar.sectiongradereportduplex',compact('collection','students','level','section','teacher','schoolyear'));
        }else{
            //return view('registrar.sectiongrade7to8',compact('collection','students','level','section','teacher','schoolyear'));
            return view('registrar.sectiongrade7to8duplex',compact('collection','students','level','section','teacher','schoolyear'));
        }
                
    }    
    
    function viewSectionKinder($level,$section,$quarter){
        $schoolyear = \App\CtrRefSchoolyear::first();
        $collection = array();
        $students = DB::Select("SELECT department,users.idno,users.lastname, users.firstname,users.middlename,users.extensionname,student_infos.lrn,gender,birthDate from users left join statuses on users.idno = statuses.idno left join student_infos on users.idno=student_infos.idno where statuses.status IN (2,3) AND level LIKE '$level' AND section LIKE '$section' ORDER BY statuses.class_no ASC");

        $matchfield = ["level"=>$level,"section"=>$section];
        $teacher = \App\CtrSection::where($matchfield)->first();        
        
        foreach($students as $student){
                    $match = ["idno"=>$student->idno,"subjecttype"=>0,"schoolyear"=>$schoolyear->schoolyear,"isdisplaycard"=>1];
                    $academic = \App\Grade::where($match)->orderBy("sortto","ASC")->get();


                    $match2 = ["idno"=>$student->idno,"subjecttype"=>3,"schoolyear"=>$schoolyear->schoolyear];
                    $conduct = \App\Grade::where($match2)->orderBy("sortto","ASC")->get();

                    
                    $match3 = ["idno"=>$student->idno,"schoolyear"=>$schoolyear->schoolyear];
                    $attendance = \App\Attendance::where($match3)->orderBy("sortto","ASC")->get();
                    
//                    $match3 = ["idno"=>$student->idno,"subjecttype"=>2,"schoolyear"=>$schoolyear->schoolyear];
//                    $attendance = \App\Grade::where($match3)->orderBy("sortto","ASC")->get();
                    
                                        $match4 = ["idno"=>$student->idno,"subjecttype"=>1,"schoolyear"=>$schoolyear->schoolyear];
                    $technical = \App\Grade::where($match4)->orderBy("sortto","ASC")->get();
                    
                    $match5 = ["idno"=>$student->idno,"quarter"=>$quarter,"schoolyear"=>$schoolyear->schoolyear];
                    $competence = \App\Competency::where($match5)->orderBy("sortto","ASC")->get();
                    
                    $age_year = date_diff(date_create($student->birthDate), date_create('today'))->y;
                    $age_month = date_diff(date_create($student->birthDate), date_create('today'))->m;
                    if($age_month == 1){
                    $age= $age_year." years ".$age_month." month";
                    }
                    else{
                    $age= $age_year." years ".$age_month." months";
                    }
                    $student->age = $age;
                    
                    $collection[] = array('info'=>$student,'aca'=>$academic,'con'=>$conduct,'att'=>$attendance,'tech'=>$technical,'comp'=>$competence);
                }           
                
        return view('registrar.sectiongradeKinder2',compact('collection','students','level','section','teacher','quarter','schoolyear'));
                
    }
    
    function studentGrade($idno){
        $schoolyears = \App\Grade::where('idno',$idno)->groupBy('schoolyear')->get();
        $collection = array();
        
        foreach($schoolyears as $schoolyear){
            $subjects = \App\Grade::where('schoolyear',$schoolyear->schoolyear)->where('idno',$idno)->orderBy('sortto','ASC')->get();
            
            $collection[] = $subjects;
        } 
        
        return $collection;
        
    }

    function overallRank(){
        $levels = DB::Select("Select level from ctr_levels");
        
        return view('vincent.registrar.overallRanking',compact('levels'));
    }
    
    function elemTor($idno){
        $papersize = array(0,0,360,360);    
        $pdf = \App::make('dompdf.wrapper');
        //$pdf->setPaper($papersize);
        $pdf->loadView("vincent.registrar.studenttor");
        return $pdf->stream();
    }

}
