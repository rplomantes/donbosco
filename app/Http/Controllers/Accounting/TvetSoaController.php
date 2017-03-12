<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class TvetSoaController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }
    
    function tvetsoa(){
        $batch = DB::Select("Select distinct period from statuses where department = 'TVET'");
        $sys = DB::Select("Select min(schoolyear) as schoolyear from statuses where department = 'TVET'");
        return view('accounting.tvetsoa',compact('batch','sys'));
    }
    
    function gettvetsoasummary(Request $request){
        session()->put('remind', $request->reminder);
        $trandate = date('Y-m-d',strtotime($request->day.'-'.$request->month.'-'.$request->year));

        if($request->section=="All"){
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$request->batch' and statuses.status = '2' and statuses.course = '$request->course' and ledgers.duedate <= '$trandate'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");

        }
        else{
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$request->batch' and statuses.status = '2' and statuses.course = '$request->course' and statuses.section = '$request->section' and ledgers.duedate <= '$trandate'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");
        }

        return view('accounting.showtvetsoa',compact('soasummary','request','trandate'));
        //return $trandate;
    }
    
    function printtvetsoasummary($period,$course,$section,$trandate,$display){
        if($display == 1){
            $condiiton = " having sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) < 0";
        }elseif($display == 2){
            $condiiton = " having sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) > 0";
        }else{
            $condiiton = "";
        }
        if($section=="All"){
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$period' and statuses.status = '2' and statuses.course = '$course' and ledgers.duedate <= '$trandate' "
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level ".$condiiton
                    . " order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");

        }
        else{
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$period' and statuses.status = '2' and statuses.course = '$course' and statuses.section = '$section' and ledgers.duedate <= '$trandate'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level ".$condiiton
                    . " order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");
        }
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadview('print.printtvetsoasummary',compact('soasummary','period','course','section','trandate','display'));
        return $pdf->stream();
    }
    
    function printtvetallsoa($period,$course,$section,$trandate,$display){
        if($display == 1){
            $condiiton = " having sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) < 0";
        }elseif($display == 2){
            $condiiton = " having sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) > 0";
        }else{
            $condiiton = "";
        }
        if($section=="All"){
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$period' and statuses.status = '2' and statuses.course = '$course'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level ".$condiiton
                    . " order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");

        }
        else{
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$period' and statuses.status = '2' and statuses.course = '$course' and statuses.section = '$section'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level ".$condiiton
                    . " order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");
        }

        $reminder = session('remind');
        
        return view('print.printalltvetsoa',compact('soasummary','period','course','section','trandate','reminder'));
        
    }
}
