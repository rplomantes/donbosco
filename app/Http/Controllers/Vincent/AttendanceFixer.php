<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Excel;

class AttendanceFixer extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function index(){
        $levels = \App\CtrLevel::all();
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $quarter = 3;
        $sections = \App\CtrSection::whereIn('level',$levels->pluck('level')->toArray())->where('schoolyear',$schoolyear)->get();
        
        return view('sys_admin.upload.attendanceFix',compact('levels','schoolyear','sections','quarter'));
    }
    
    function getStartRow(){
        
            $path = $this->getFile();
            $sheet = $this->getSheet();
            
            ini_set('max_execution_time', 0);
            Excel::selectSheets($sheet)->load($path, function($reader) {
                        $range = 20;
                        $start = 1;
                        $column = "A";
                        
                        $reader->noHeading();
                        do{
                            $cell = $column.$start;
                            
                            $cellValue = $reader->getActiveSheet()->getCell($cell)->getValue();
                            
                            if($cellValue == "CN" || $cellValue == "Class No."){
                                session()->flash('rowStart', $start);
                            }
                            
                            $start++;
                        }while($start <= $range);
                        
                        
			});
    }
    
    function get_elemIdno(){
            $path = $this->getFile();
            $sheet = "RgstrCopy";
            $row = session('rowStart')+1;
            
            Excel::selectSheets($sheet)->load($path, function($reader) use($row){
                $col_idno = "B";
                $col_cn = "A";
                $idnos = array();
                
                while(!in_array($reader->getActiveSheet()->getCell($col_cn.$row)->getOldCalculatedValue(),array(""," "))){
                    $idnos[$reader->getActiveSheet()->getCell($col_cn.$row)->getOldCalculatedValue()] = $reader->getActiveSheet()->getCell($col_idno.$row)->getOldCalculatedValue();
                    
                    $row++;
                }
                
                session()->flash('idnos', $idnos);
            });
    }
    
    function fetchFromExcel(){
        $this->getStartRow();
        $this->get_elemIdno();
        
            $path = $this->getFile();
            $sheets = $this->getSheet();
            
            Excel::selectSheets($sheets)->load($path, function($reader) {
                $idnos = session('idnos');
                
                foreach($reader->get() as $sheet){
                    $row = session('rowStart')+1;
                    $sheetTitle = $sheet->getTitle();
                    
                    $col_name = $this->getColName($sheetTitle);
                    $reader->setActiveSheetIndexByName($sheetTitle);
                    
                    while(!in_array($reader->getActiveSheet()->getCell('A'.$row)->getOldCalculatedValue(),array(""," "))){
                        $col_cn = 'A';
                        $col_tardy = 'AO';
                        $col_absent = 'AQ';
                        $col_present = 'AS';
                        
                        $idno = $idnos[$reader->getActiveSheet()->getCell($col_cn.$row)->getOldCalculatedValue()];

                        $tardy = $reader->getActiveSheet()->getCell($col_tardy.$row)->getOldCalculatedValue();
                        $this->postAttendance($idno,$tardy,'DAYT',3,$col_name);

                        $absent = $reader->getActiveSheet()->getCell($col_absent.$row)->getOldCalculatedValue();
                        $this->postAttendance($idno,$absent,'DAYA',3,$col_name);

                        $present = $reader->getActiveSheet()->getCell($col_present.$row)->getOldCalculatedValue();
                        $this->postAttendance($idno,$present,'DAYP',3,$col_name);

                        $row++;
                    }
                
                }
                $firststudent = \App\Status::where('idno',$idnos[1])->where('schoolyear',2017)->first();
                $this->writeFinishedAtt($firststudent->level,$firststudent->section,3,$firststudent->schoolyear);
                
            });
            
            return redirect()->route('attFixer');
    }
    
    function postAttendance($idno,$value,$type,$quarter,$month,$schoolyear=2017){
        $filter = ['idno'=>$idno,'attendancetype'=>$type,'quarter'=>$quarter,'schoolyear'=>$schoolyear];
        $attendance = \App\Attendance::where('idno',$idno)->where($filter)->first();
        if($attendance){
            $attendance->$month = $value;
            $attendance->save();
        }else{
            $otherInfo = $this->getAttendanceInfo($type);
            
            $newAttendance = new \App\Attendance();
            $newAttendance->idno = $idno;
            $newAttendance->quarter = $quarter;
            $newAttendance->attendancetype = $type;
            $newAttendance->attendanceName = $otherInfo['name'];
            $newAttendance->sortto = $otherInfo['order'];
            $newAttendance->$month = $value;
            
            $newAttendance->schoolyear = $schoolyear;
            $newAttendance->save();
            
        }
    }
    
    function writeFinishedAtt($level,$section,$quarter,$schoolyear=2017){
        $attStat = new \App\GradesStatus();
        $attStat->level = $level;
        $attStat->section = $section;
        $attStat->quarter = $quarter;
        $attStat->gradetype = 2;
        $attStat->schoolyear = $schoolyear;
        $attStat->status = 2;
        $attStat->save();
        
    }
    
    static function getAttendanceInfo($type){
        switch($type){
            case 'DAYP':
                return array('name'=>"Days Present",'order'=>1);
                break;
            case 'DAYA':
                return array('name'=>"Days Absent",'order'=>2);
                break;
            case 'DAYT':
                return array('name'=>"Days Tardy",'order'=>3);
                break;
            default:
                return array('name'=>"",'order'=>0);
                break;
        }
    }
    
    function getFile(){
        return Input::file('import_file')->getRealPath();
    }
    
    function getSheet(){
        return array("October","November","December","January");
    }
    
    function testing(){
        echo "me";
    }
    
    function getColName($sheetTitle){
        switch ($sheetTitle){
            case "January":
                return 'Jan';
            case "February":
                return 'Feb';
            case "March":
                return 'Mar';
            case "June":
                return 'Jun';
            case "July":
                return 'Jul';
            case "August":
                return 'Aug';
            case "September":
                return 'Sept';
            case "October":
                return 'Oct';
            case "November":
                return 'Nov';
            case "December":
                return 'Dece';
            default:
                return 'idno';
        }
    }
}
