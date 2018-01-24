<?php

namespace App\Http\Controllers\Registrar\Upload;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Excel;

class Conducts extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }
    function index(){
        $levels = \App\CtrLevel::all();
        
        return view('sys_admin.upload.conducts',compact('levels'));
    }
    
    function postconducts(Request $request){
        $level = $request->level;
        $conducts = $this->getConducts($level);
        $this->getStartCell($conducts);
        $this->fetchFromExcel($conducts);
        $section = $this->getSection();
        $uploads = session('uploaded');
        
        return view('sys_admin.upload.uploadedConducts',compact('level','conducts','uploads','section'));
    }
    
    function getStartCell(){
            $path = $this->getFile();
            $sheet = $this->getSheet();
            
            
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
    
    function fetchFromExcel($conducts){
            $path = $this->getFile();
            $sheet = $this->getSheet();
            $conductcodes = $conducts->pluck('subjectcode')->toArray();
            
            Excel::selectSheets($sheet)->load($path, function($reader) use($conductcodes) {
                        $row = session('rowStart')+1;
                        
                        $uploaded = array();
                        while(!in_array($reader->getActiveSheet()->getCell('A'.$row)->getOldCalculatedValue(),array(""," "))){
                            $idno = $reader->getActiveSheet()->getCell('B'.$row)->getOldCalculatedValue();
                            $conduct = array();
                            
                            $conductCol = 'G';
                            foreach ($conductcodes as $conductcode){
                                $conduct[$conductcode] = $reader->getActiveSheet()->getCell($conductCol.$row)->getOldCalculatedValue();
                                $conductCol++;
                            }
                            
                            $uploaded[$idno] = $conduct;
                            $row++;
                        };
                        session()->flash('uploaded', $uploaded);
			});
    }
    
    function saveConduct(Request $request){
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $field = $this->getGradeQuarter($quarter);
        $conducts = $request->conduct;
        
        foreach($conducts as $key=>$conduct){
            $idno = $key;
            foreach($conduct as $key=>$grade){
                \App\Grade::where('idno',$idno)->where('schoolyear',$schoolyear)->where('subjectcode',$key)->update([$field=>$grade]);
            }
            
        }
    }
    
    
    function getSection(){
            $path = $this->getFile();
            $sheet = $this->getSheet();
            
            
            Excel::selectSheets($sheet)->load($path, function($reader) {
                $reader->noHeading();
                    $searchFrom = "H";
                    $range = 3;
                    $start = 1;
                        do{                            
                            $cellValue = $reader->getActiveSheet()->getCell($searchFrom."4")->getValue();
                            
                            if(!in_array($cellValue,array(""," "))){
                                session()->flash('excelsection', $cellValue);
                            }
                            
                            $start++;
                            $searchFrom++;
                        }while($start <= $range);
                    
                        
                });
                
             return session('excelsection');
    }
    
    function getConducts($level){
        $conducts = \App\CtrSubjects::where('level',$level)->where('subjecttype',3)->orderBy('sortto','ASC')->get();
        return $conducts;
    }
    
    function getFile(){
        return Input::file('import_file')->getRealPath();
    }
    
    function getSheet(){
        return "3rd QTR";
    }
    
    function getGradeQuarter($quarter){
        switch($quarter){
            case 1:
                $quarter = "first_grading";
                break;
            case 2:
                $quarter = "second_grading";
                break;
            case 3:
                $quarter = "third_grading";
                break;
            case 4:
                $quarter = "fourth_grading";
                bfreak;
            default:
                $quarter = "final_grade";
                break;
        }
        
        return $quarter;
    }
}
