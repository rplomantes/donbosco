<?php

namespace App\Http\Controllers\Registrar\Section;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SectionInformation extends Controller
{
    static function section_Studentlist($sy,$level,$section){
        $status = \App\Status::where('level',$level)->where('schoolyear',$sy)->whereIn('status',array(2,3))->where('section',$section)->get();
                
        $status_history = where('level',$level)->where('schoolyear',$sy)->whereIn('status',array(2,3))->where('section',$section)->get();
        
        $students = $status->union($status_history)->unique('idno')->sortBy('class_no');
        
        return $students;
    }
    
    static function section_adviser($sy,$level,$section){
        $section = \App\CtrSection::where('level',$level)->where('schoolyear',$sy)->where('section',$section)->first();
        
        if(!$section){
            $section = \App\CtrSectionHistory::where('level',$level)->where('schoolyear',$sy)->where('section',$section)->first();
        }
    }
    
    static function section_list($sy,$level){
        $sections  = \App\CtrSection::where('schoolyear',$sy)->where('level',$level)->get()->distinct('section');
        if(!(count($sections) > 0)){
            $sections  = \App\CtrSectionHistory::where('schoolyear',$sy)->where('level',$level)->get()->distinct('section');
        }
        
        return $sections;
    }
    
    static function section_status($section){
        
        if($section->status == 0){
            return "<i class='fa fa-unlock'></i>";
        }else{
            return "<i class='fa fa-lock'></i>";
        }
        
        
    }
    

}
