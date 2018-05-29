<?php

namespace App\Http\Controllers\Registrar\Section;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Section\SectionHelper;

class SectionStudents extends Controller
{
    function sectioning_index($level=null,$strand=null){
        $schoolyear = \App\CtrYear::getYear('schoolyear');
        
        $sections = \App\CtrSection::where('schoolyear',$schoolyear)->where('level',$level)->orderBy('sortto','ASC')->get();
        if($strand != null){
            $sections = $sections->where('strand',$strand,false);
        }
        
        $levels = \App\CtrLevel::all();
        return view('registrar.section.sectioning.index',compact('level','strand','schoolyear','levels','sections'));
    }
    
    function auto_section($level,$strand=null){
        $schoolyear = \App\CtrYear::getYear('schoolyear');
        $sections = \App\CtrSection::where('schoolyear',$schoolyear)->where('level',$level)->orderBy('sortto','ASC')->get();
        
        if($strand != null){
            $sections = $sections->where('strand',$strand,false);
        }
        
        $students = SectionHelper::get_StudentList($schoolyear, $level, $strand);
        
        if($students->count() > 0 && $sections->count() > 0){
            
            $idealCount = (int)($students->count()/$sections->count());
            echo $idealCount."<br>";
            foreach($sections as $section){
                $remaining = $idealCount - $section->students->count();
                $unassigneds = SectionHelper::get_StudentList($schoolyear, $level, $strand)->shuffle();
                if(count($unassigneds) > 0){
                    
                    $this->set_autoSection($section, $remaining, $unassigneds);
                }
                
            }
            
        }
        
        return redirect()->back();
    }
    
    function set_autoSection($section,$remaining,$unassigneds){
        
        foreach($unassigneds as $unassigned){
            if($remaining <= 0){
                return null;
            }
            echo $unassigned->section;
            if($unassigned->section == ''){
                SectionHelper::set_studentSection($unassigned->idno,$section->section);
                $remaining--;
            }

        }
    }
    
    static function view_levelList($schoolyear,$level,$strand=null){
        $students = SectionHelper::get_StudentList($schoolyear, $level, $strand);
        return view('registrar.section.sectioning.levelList',compact('students'))->render();
    }
    
    static function view_sectionList($schoolyear,$section,$level,$strand=null){
        $students = SectionHelper::get_StudentList($schoolyear, $level, $strand,$section);
        return view('registrar.section.sectioning.sectionList',compact('students'));
    }
}
