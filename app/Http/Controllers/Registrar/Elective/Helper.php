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
                . "and s.section = cs.section "
                . "and s.schoolyear = cs.schoolyear "
                . "where g.section = $sectionid "
                . "order by cs.sortto ASC,class_no ASC");
        
        return $students;
    }
}
