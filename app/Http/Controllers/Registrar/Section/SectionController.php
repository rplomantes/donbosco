<?php

namespace App\Http\Controllers\Registrar\Section;

use Illuminate\Http\Request;
use App\Http\Requests\Registrar\Section\UpdateSectionRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Registrar\Section\SectionInformation as SecInfo;
use Illuminate\Support\Facades\Input;
use Excel;
use DB;

class SectionController extends Controller
{
    public function __construct(){
        $this->middleware('auth',['only'=>['viewCreateSection']]);
    }
    
    function viewCreateSection(){        
        return view('registrar.section.createSection.index');
    }
    
    function saveSection(Request $request){
        $sy = \App\CtrYear::where('type','schoolyear')->first()->year;
        
        $validator = $this->verify($request->all());
        if($validator->fails()){
            return view('registrar.section.createSection.index')->withErrors($validator);
        }
        if($request->strand == null){
            $strand = "";
        }else{
            $strand = $request->strand;
        }
        
        $newSection = new \App\CtrSection();
        $newSection->level = $request->level;
        $newSection->schoolyear = $sy;
        $newSection->strand = $strand;
        $newSection->section = $request->section;
        $newSection->adviserId = $request->adviser;
        $newSection->save();
        
        return view('registrar.section.createSection.index');
    }
    
    function verify(array $data){
        return Validator::make($data, [
            'level' => 'required',
            'strand' => 'required_if:level,Grade 9|required_if:level,Grade 10|required_if:level,Grade 11|required_if:level,Grade 12',
            'section' => 'required|unique:ctr_sections,level,section,schoolyear',
        ]);
    }
    
    function uploadSections(Request $request){
        if(Input::hasFile('sections')){
            $schoolyear = \App\CtrYear::getYear('schoolyear');
            $path = Input::file('sections')->getRealPath();
            $data = Excel::selectSheets('sections')->load($path, function($reader) {
            })->get()->flatten(1);
            if(!empty($data) && $data->count()){
                foreach($data as $key=>$value){
                    $insert[] = ['level' =>$value->level, 'strand' => $value->strand_course,'section'=> $value->section,'adviserid'=> $value->adviser_id,'schoolyear'=> $schoolyear,'sortto'=>$value->sort];
                }
                DB::table('ctr_sections')->insert($insert);
            }
        }
        return redirect()->back();
    }
    
    function deleteSection(Request $request){
        $section = \App\CtrSection::find($request->section);
        $section->delete();
        
        return redirect()->back();
    }
    
    function updateSection(UpdateSectionRequest $request){
        $section = \App\CtrSection::find($request->section);
        
        if($section){
            $section->section = $request->sectionname;
            $section->adviserid = $request->adviser;
            $section->save();
        }
        return redirect()->back();
    }
    //View Rendedrers
    static function renderForm(){
        $get_position = \App\Position::where('position','Adviser')->first();
        $position = 0;
        if($get_position){
            $position = $get_position->id;
        }
        $advisers = \App\UsersPosition::with(['users'=>function($query){
            $query->where('accesslevel','!=',0);
        }])->where('position',$position)->get();

        
        $sy = \App\CtrYear::where('type','schoolyear')->first()->year;
        $levels = \App\CtrLevel::all();
        
        
        
        return view('registrar.section.createSection.form',compact('levels','sy','advisers'))->render();
    }
    
    static function renderSectionList(){
        $sy = \App\CtrYear::where('type','schoolyear')->first()->year;
        $sections = \App\CtrSection::where('schoolyear',2018)->get();
        
        return view('registrar.section.createSection.list',compact('sections'))->render();
        
    }
    
    function change_sectionStatus($id){
        $section = \App\CtrSection::find($id);
        
        if($section){
            if($section->status == 1){
                $section->status = 0;
                $section->save();
            }else{
                $section->status = 0;
                $section->save();
            }
        }
        
        return SecInfo::section_status($section);
    }
    
}
