<?php

namespace App\Http\Controllers\Registrar\Elective;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class Helper extends Controller
{
    static function studentsectionlist($sectionid){
        $students = DB::Select("Select distinct s.idno from grades g "
                . "join statuses s on g.idno = s.idno "
                . "join ctr_sections cs on s.level = cs.level "
                . "join users u on s.idno = u.idno "
                . "and s.section = cs.section "
                . "and s.schoolyear = cs.schoolyear "
                . "where g.section = $sectionid "
                . "order by cs.sortto ASC,u.lastname ASC, u.firstname ASC");
        
        
        return $students;
    }
    
    static function electiveadviser($section){
        $adviser = \App\CtrElectiveSection::find($section);
        $advisername = "";
        $user = \App\User::where('idno',$adviser->adviser)->first();
        if($user){
            $advisername = $user->firstname." ".substr($user->middlename,0,1).". ".$user->lastname;
        }
        return $advisername;
    }
    
    static function sectionName($section){
        $sectionname = \App\CtrElectiveSection::find($section);
        $elective = $sectionname->elective." (".$sectionname->section.")";
        return $elective;
    }
}
