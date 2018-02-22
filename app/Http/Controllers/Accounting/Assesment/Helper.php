<?php

namespace App\Http\Controllers\Accounting\Assesment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    function get_strand_course($level){
        $strands = \App\CtrLevelsStrand::where('level',$level)->get();
        
        if(count($strands)>0){
            return view('ajax.selecttrackstrand',compact('strands'));
        }else{
            return "";
        }
        
    }
}
