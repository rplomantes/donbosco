<?php

namespace App\Http\Controllers\Registrar\Section;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Adviser\AdvisorHelper;
use App\Http\Controllers\Registrar\Section\SectionStudents;
use App\Http\Controllers\Registrar\Section\SectionHelper;

class SectionAjax extends Controller
{
    function modal_deleteSection($id){
        $section = \App\CtrSection::find($id);
        
        if($section){
            return view('registrar.section.modal.deleteSection',compact('section'))->render();
        }
    }
    
    function modal_updateSection($id){
        $section = \App\CtrSection::find($id);
        $advisers = AdvisorHelper::get_advisorList();
        if($section){
            return view('registrar.section.modal.updateSection',compact('section','advisers'))->render();
        }
    }
    
    function get_sectionList(Request $request){
        $schoolyear = $request->sy;
        $level = $request->level;
        if($request->strand == 'null'){
            $strand = null;
        }else{
            $strand = $request->strand;
        }
        
        $section = $request->section;
        
        return SectionStudents::view_sectionList($schoolyear, $section, $level, $strand);
                
    }
    
    function get_levelList(Request $request){
        $schoolyear = $request->sy;
        $level = $request->level;
        if($request->strand == 'null'){
            $strand = null;
        }else{
            $strand = $request->strand;
        }
        
        
        return SectionStudents::view_levelList($schoolyear, $level, $strand);
                
    }
    
    function set_studentSection(Request $request){
        $section = $request->section;
        $idno = $request->idno;
        
        SectionHelper::set_studentSection($idno, $section);

    }
}
