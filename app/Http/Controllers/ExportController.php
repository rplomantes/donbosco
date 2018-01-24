<?php

namespace App\Http\Controllers;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;

class ExportController extends Controller
{
    	public function importGrade()

	{

		return view('registrar.importgrade');

	}

	public function downloadExcel($type)

	{

		$data = Item::get()->toArray();

		return Excel::create('itsolutionstuff_example', function($excel) use ($data) {

			$excel->sheet('mySheet', function($sheet) use ($data)

	        {

				$sheet->fromArray($data);

	        });

		})->download($type);

	}

	public function importExcelGrade(){
		if(Input::hasFile('import_file')){
                        $qtrperiod = \App\CtrQuarter::first()->qtrperiod;
                        $schoolyear = \App\ctrSchoolYear::first();
                        $sy = $schoolyear->schoolyear;
			$path = Input::file('import_file')->getRealPath();
			$data = Excel::selectSheets('Sheet1')->load($path, function($reader) {
			})->get();
			if(!empty($data) && $data->count()){
				foreach ($data as $key => $value) {
                                    /*
					$insert[] = ['idno' => $value->idno, 'subjectcode' => $value->subjectcode,
                                            'level'=> $value->level, 'section'=> $value->section, 'grade'=> $value->grade,
                                            'qtrperiod'=> $value->qtrperiod,'schoolyear'=> $value->schoolyear];
                                      */  
                                    $idnof = $value->idno;
                                    if(strlen($idnof)==5){
                                        $idnof = "0".$idnof;
                                    }
                                        $insert[] = ['idno' => $idnof, 'subjectcode' => $value->subjectcode,
                                            'grade'=> $value->grade,
                                            'qtrperiod'=> $qtrperiod,'schoolyear'=> $sy];
                                        
                                 $this->upgradegrade($value->idno, $value->subjectcode, $qtrperiod,$value->grade,$sy);
				
                                 
                                }

				if(!empty($insert)){

					DB::table('subject_repos')->insert($insert);

					//dd('Insert Record successfully.');

				}

			}

		}
                //return $insert;
		return redirect(url('/importGrade'));

	}
        
        public function importExcelConduct()

	{
            if(Input::hasFile('import_file1')){
                        $qtrperiod = \App\CtrQuarter::first()->qtrperiod;
                        $schoolyear = \App\ctrSchoolYear::first();
                        $sy = $schoolyear->schoolyear;
			$path = Input::file('import_file1')->getRealPath();
                        
			$data = Excel::selectSheets('Sheet1')->load($path, function($reader) {
			})->get();
			if(!empty($data) && $data->count()){
                           
				foreach ($data as $key => $value) {
                                    $idnof = $value->idno;
                                    if(strlen($idnof)==5){
                                        $idnof = "0".$idnof;
                                    }
                                    
                                    $insert[] = ['idno'=>$idnof, 'qtrperiod'=>$qtrperiod, 
                                        'schoolyear'=>$value->schoolyear,
                                        'GC'=>$value->gc, 'SR'=>$value->sr,'PE'=>$value->pe, 'SYN'=>$value->syn,
                                        'JO'=>$value->jo,'TS'=>$value->ts,'OSR'=>$value->osr,'DPT'=>$value->dpt,'PTY'=>$value->pty,
                                        'DI'=>$value->di,'PG'=>$value->pg,'SIS'=>$value->sis];
                                    
                                   $this->updateconduct($value->idno, 'GC', $value->gc, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'SR', $value->sr, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'PE', $value->pe, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'SYN', $value->syn, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'JO', $value->jo, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'TS', $value->ts, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'OSR', $value->osr, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'DPT', $value->dpt, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'PTY', $value->pty, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'DI', $value->di, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'PG', $value->pg, $qtrperiod, $sy);
                                   $this->updateconduct($value->idno, 'SIS', $value->sis, $qtrperiod, $sy);
				}

				if(!empty($insert)){

					DB::table('conduct_repos')->insert($insert);
					

				}

			}

		}
                //return $insert;
		return redirect(url('/importGrade'));
		

	}
        
        public function importExcelCompetence()

	{

		if(Input::hasFile('import_file3')){
                        $qtrperiod = \App\CtrQuarter::first();
                        $schoolyear = \App\ctrSchoolYear::first();
                        $sy = $schoolyear->schoolyear;
			$path = Input::file('import_file3')->getRealPath();
                        
			$data = Excel::selectSheets('Sheet1')->load($path, function($reader) {
			})->get();
			if(!empty($data) && $data->count()){
                           
				foreach ($data as $key => $value) {
                                    $idnof = $value->idno;
                                    if(strlen($idnof)==5){
                                        $idnof = "0".$idnof;
                                    }
                                    
                                    $insert[] = ['idno'=>$idnof, 
                                        'qtrperiod'=>$qtrperiod->qtrperiod, 
                                        'schoolyear'=>$value->schoolyear,
                                        'CL11'=>$value->cl12,
                                        'CL21'=>$value->cl22,
                                        'CL31'=>$value->cl32,
                                        'CL41'=>$value->cl42,
                                        'CL51'=>$value->cl52,
                                        'CL61'=>$value->cl62,
                                        'CL71'=>$value->cl72,
                                        'CL81'=>$value->cl82,
                                        'CL91'=>$value->cl92,
                                        'ENGL11'=>$value->engl12,
                                        'ENGL21'=>$value->engl22,  
                                        'ENGL31'=>$value->engl32,
                                        'ENGL41'=>$value->engl42,
                                        'ENGL51'=>$value->engl52,
                                        'ENGL61'=>$value->engl62,
                                        'ENGL71'=>$value->engl72,
                                        'ENGL81'=>$value->engl82,
                                        'ENGL91'=>$value->engl92,
                                        'ENGL101'=>$value->engl102,
                                        'MATH11'=>$value->math12,
                                        'MATH21'=>$value->math22,
                                        'MATH31'=>$value->math32,
                                        'MATH41'=>$value->math42,
                                        'MATH51'=>$value->math52,
                                        'MATH61'=>$value->math62,
                                        'MATH71'=>$value->math72,
                                        'MATH81'=>$value->math82,
                                        'MATH91'=>$value->math92,
                                        'MATH101'=>$value->math102,
                                        'FIL11'=>$value->fil12,
                                        'FIL21'=>$value->fil22,
                                        'FIL31'=>$value->fil32,
                                        'FIL41'=>$value->fil42,
                                        'FIL51'=>$value->fil52,
                                        'FIL61'=>$value->fil62,
                                        'FIL71'=>$value->fil72,
                                        'FIL81'=>$value->fil82,
                                        'FIL91'=>$value->fil92,
                                        ];
                                    
                                   $this->upgradecompetence($idnof,'CL12',$qtrperiod->qtrperiod, $value->cl12,$sy);
                                   $this->upgradecompetence($idnof,'CL22',$qtrperiod->qtrperiod, $value->cl22,$sy);
                                   $this->upgradecompetence($idnof,'CL32',$qtrperiod->qtrperiod, $value->cl32,$sy);
                                   $this->upgradecompetence($idnof,'CL42',$qtrperiod->qtrperiod, $value->cl42,$sy);
                                   $this->upgradecompetence($idnof,'CL52',$qtrperiod->qtrperiod, $value->cl52,$sy);
                                   $this->upgradecompetence($idnof,'CL62',$qtrperiod->qtrperiod, $value->cl62,$sy);
                                   $this->upgradecompetence($idnof,'CL72',$qtrperiod->qtrperiod, $value->cl72,$sy);
                                   $this->upgradecompetence($idnof,'CL82',$qtrperiod->qtrperiod, $value->cl82,$sy);                                   
                                   $this->upgradecompetence($idnof,'CL92',$qtrperiod->qtrperiod, $value->cl92,$sy);                                   
                                   
                                   $this->upgradecompetence($idnof,'ENGL12',$qtrperiod->qtrperiod, $value->engl12,$sy);
                                   $this->upgradecompetence($idnof,'ENGL22',$qtrperiod->qtrperiod, $value->engl22,$sy);
                                   $this->upgradecompetence($idnof,'ENGL32',$qtrperiod->qtrperiod, $value->engl32,$sy);
                                   $this->upgradecompetence($idnof,'ENGL42',$qtrperiod->qtrperiod, $value->engl42,$sy);
                                   $this->upgradecompetence($idnof,'ENGL52',$qtrperiod->qtrperiod, $value->engl52,$sy);
                                   $this->upgradecompetence($idnof,'ENGL62',$qtrperiod->qtrperiod, $value->engl62,$sy);
                                   $this->upgradecompetence($idnof,'ENGL72',$qtrperiod->qtrperiod, $value->engl72,$sy);
                                   $this->upgradecompetence($idnof,'ENGL82',$qtrperiod->qtrperiod, $value->engl82,$sy);
                                   $this->upgradecompetence($idnof,'ENGL92',$qtrperiod->qtrperiod, $value->engl92,$sy);
                                   $this->upgradecompetence($idnof,'ENGL102',$qtrperiod->qtrperiod, $value->engl102,$sy);

                                   
                                   $this->upgradecompetence($idnof,'MATH12',$qtrperiod->qtrperiod, $value->math12,$sy);
                                   $this->upgradecompetence($idnof,'MATH22',$qtrperiod->qtrperiod, $value->math22,$sy);
                                   $this->upgradecompetence($idnof,'MATH32',$qtrperiod->qtrperiod, $value->math32,$sy);
                                   $this->upgradecompetence($idnof,'MATH42',$qtrperiod->qtrperiod, $value->math42,$sy);
                                   $this->upgradecompetence($idnof,'MATH52',$qtrperiod->qtrperiod, $value->math52,$sy);
                                   $this->upgradecompetence($idnof,'MATH62',$qtrperiod->qtrperiod, $value->math62,$sy);
                                   $this->upgradecompetence($idnof,'MATH72',$qtrperiod->qtrperiod, $value->math72,$sy);
                                   $this->upgradecompetence($idnof,'MATH82',$qtrperiod->qtrperiod, $value->math82,$sy);
                                   $this->upgradecompetence($idnof,'MATH92',$qtrperiod->qtrperiod, $value->math92,$sy);
                                   $this->upgradecompetence($idnof,'MATH102',$qtrperiod->qtrperiod, $value->math102,$sy);

                                   $this->upgradecompetence($idnof,'FIL12',$qtrperiod->qtrperiod, $value->fil12,$sy);
                                   $this->upgradecompetence($idnof,'FIL22',$qtrperiod->qtrperiod, $value->fil22,$sy);
                                   $this->upgradecompetence($idnof,'FIL32',$qtrperiod->qtrperiod, $value->fil32,$sy);
                                   $this->upgradecompetence($idnof,'FIL42',$qtrperiod->qtrperiod, $value->fil42,$sy);
                                   $this->upgradecompetence($idnof,'FIL52',$qtrperiod->qtrperiod, $value->fil52,$sy);
                                   $this->upgradecompetence($idnof,'FIL62',$qtrperiod->qtrperiod, $value->fil62,$sy);
                                   $this->upgradecompetence($idnof,'FIL72',$qtrperiod->qtrperiod, $value->fil72,$sy);
                                   $this->upgradecompetence($idnof,'FIL82',$qtrperiod->qtrperiod, $value->fil82,$sy);
                                   $this->upgradecompetence($idnof,'FIL92',$qtrperiod->qtrperiod, $value->fil92,$sy);

                              }
                             

				if(!empty($insert)){

					DB::table('competency_repos')->insert($insert);

				//return $insert;	

				}

			}

		}
                //return $insert;
		return redirect(url('/importGrade'));

	}
        
        public function upgradegrade($idno,$subjectcode,$qtrperiod,$grade,$sy){
        if(strlen($idno)==5){
            $idno = "0".$idno;
        }     
        $qtrname = "";
        switch($qtrperiod){
            case 1:
                $qtrname = "first_grading";
                break;
            case 2:
                $qtrname="second_grading";
                break;
            case 3:
                $qtrname="third_grading";
                break;
            case 4:
                $qtrname="fourth_grading";
        }
        $ug = \App\Grade::where('idno',$idno)->where('subjectcode',$subjectcode)->where('schoolyear',$sy)->first();
        if(count($ug)>0){
        $ug->$qtrname = $grade;
        $ug->update();    
        }
        
        }
        
        public function updateattendance($idno,$daya,$dayp,$dayt,$qtrperiod,$sy){
        if(strlen($idno)==5){
            $idno = "0".$idno;
        }    
        $qtrname = "";
        switch($qtrperiod){
            case 1:
                $qtrname = "first_grading";
                break;
            case 2:
                $qtrname="second_grading";
                break;
            case 3:
                $qtrname="third_grading";
                break;
            case 4:
                $qtrname="fourth_grading";
        }
        $ug = \App\Grade::where('idno',$idno)->where('schoolyear',$sy)->where('subjectcode','DAYP')->first();
        if(count($ug)>0){
        $ug->$qtrname = $dayp;
        $ug->update(); 
        }
        
        $ug = \App\Grade::where('idno',$idno)->where('schoolyear',$sy)->where('subjectcode','DAYA')->first();
        if(count($ug)>0){
        $ug->$qtrname = $daya;
        $ug->update(); 
        }
        $ug = \App\Grade::where('idno',$idno)->where('schoolyear',$sy)->where('subjectcode','DAYT')->first();
        if(count($ug)>0){
        $ug->$qtrname = $dayt;
        $ug->update(); 
        }
        }
        
        public function updateconduct($idno,$ctype,$cvalue,$qtrperiod,$schoolyear){
          if(strlen($idno)==5){
            $idno = "0".$idno;
        }   
          if(!is_null($cvalue) || $cvalue!=""){  
            switch($qtrperiod){
            case 1:
                $qtrname = "first_grading";
                break;
            case 2:
                $qtrname="second_grading";
                break;
            case 3:
                $qtrname="third_grading";
                break;
            case 4:
                $qtrname="fourth_grading";
        }
        
        $cupate = \App\Grade::where('idno',$idno)->where('subjectcode',$ctype)->where('schoolyear',$schoolyear)->first();
        if(count($cupate)>0){
        $cupate->$qtrname=$cvalue;
        $cupate->update();
        }
          }
        }
        public function updatecompetency($idno, $qtr, $schoolyear, $competencycode, $value, $sy){
            $updatecom =  \App\Competency::where('idno',$idno)->where('competencycode',$competencycode)->where('quarter',$qtr)->where('schoolyear',$sy)->first();
            $updatecom->value=$value;
            $updatecom->save();
        }
        public function importExcelAttendance()

	{

		if(Input::hasFile('import_file2')){
                        $schoolyear = \App\ctrSchoolYear::first();
                        $sy = $schoolyear->schoolyear;
			$path = Input::file('import_file2')->getRealPath();
                        
			$data = Excel::selectSheets('Sheet1')->load($path, function($reader) {
                            $reader->skip(11);
			})->get();
			if(!empty($data) && $data->count()){
                           
				foreach ($data as $key => $value) {
                                    $idnof = $value->idno;
                                    if(strlen($idnof)==5){
                                        $idnof = "0".$idnof;
                                    }
                                   $dya=$this->noneNull($value->daya);
                                   $dyp=$this->noneNull($value->dayp);
                                   $dyt=$this->noneNull($value->dayt);
                                   
                                    $insert[] = ['idno'=>$idnof, 'qtrperiod'=>$value->qtrperiod, 
                                        'schoolyear'=>$sy,
                                        'DAYA'=>$dya, 'DAYP'=>$dyp,'DAYT'=>$dyt];
                                   
                                 $this->updateattendance($value->idno, $value->daya, $value->dayp, $value->dayt, $value->qtrperiod, $sy);
				}

				if(!empty($insert)){

					DB::table('attendance_repos')->insert($insert);

					

				}

			}

		}

		return redirect(url('/importGrade'));

	}
        
        function noneNull($value){
            if($value="" || is_null($value)){
                $value="0";
            }
            return $value;
        }
        public function upgradecompetence($idno,$competencycode,$quarter,$value,$sy){
        if(strlen($idno)==5){
            $idno = "0".$idno;
        }     
        
        $ug = \App\Competency::where('idno',$idno)->where('competencycode',$competencycode)->where('schoolyear',$sy)->where('quarter',$quarter)->first();
        if(count($ug)>0){
        $ug->value = $value;
        $ug->update();    
        }}
        
}
