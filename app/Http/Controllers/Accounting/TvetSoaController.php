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
    
    static function printTvetSoa($idno, $trandate){
          $statuses = \App\Status::where('idno',$idno)->first();
          $users = \App\User::where('idno',$idno)->first();
          $balances = DB::Select("select sum(amount) as amount , sum(plandiscount) + sum(otherdiscount) as discount, "
                  . "sum(payment) as payment, sum(debitmemo) as debitmemo, receipt_details, categoryswitch  from ledgers  where "
                  . " idno = '$idno'  and (categoryswitch <= '6' or ledgers.receipt_details LIKE 'Trainee%') group by "
                  . "receipt_details, categoryswitch order by categoryswitch");
          
          if($statuses->department == "TVET"){
          $balances = DB::Select("select sum(amount)+sum(s.discount)+sum(s.subsidy)+sum(sponsor) as amount ,sum(amount) as trainees ,sum(s.discount) as discount, sum(payment) as payment, sum(sponsor) as sponsor,"
                  . "sum(s.subsidy) as subsidy ,receipt_details from ledgers join tvet_subsidies as s on s.idno=ledgers.idno and s.batch=ledgers.period where ledgers.idno = '$idno' and ledgers.receipt_details LIKE 'Trainee%' group by receipt_details, categoryswitch order by categoryswitch");
          }
          
          $schedules=DB::Select("select sum(amount) as amount , sum(plandiscount) + sum(otherdiscount) as discount, "
                  . "sum(payment) as payment, sum(debitmemo) as debitmemo, duedate  from ledgers  where "
                  . " idno = '$idno' and (categoryswitch <= '6' or ledgers.receipt_details LIKE 'Trainee%') group by "
                  . "duedate order by duedate");

          $others=DB::Select("select sum(amount) - sum(plandiscount) - sum(otherdiscount) - "
                  . "sum(payment) - sum(debitmemo) as balance ,sum(amount) as amount , sum(plandiscount) + sum(otherdiscount) as discount,"
                  . "sum(payment) as payment, sum(debitmemo) as debitmemo, receipt_details,description, categoryswitch from ledgers  where "
                  . " idno = '$idno' and categoryswitch > '6' and ledgers.receipt_details NOT LIKE 'Trainee%'  group by "
                  . "receipt_details, transactiondate order by LEFT(receipt_details, 4) ASC,id");
          $schedulebal = 0;
          if(count($schedules)>0){
              foreach($schedules as $sched){
                  if($sched->duedate <= $trandate){
                   $schedulebal = $schedulebal + $sched->amount - $sched->discount -$sched->debitmemo - $sched->payment;
                  }
              }
          }
          $otherbalance = 0;
          if(count($others)>0){
              foreach($others as $ot){
                  $otherbalance = $otherbalance+$ot->balance;
              }
          }
          
          $transactionreceipts = DB::Select("select transactiondate,receiptno,amount from "
                  . "(select transactiondate,receiptno,sum(amount)+sum(checkamount) as amount from dedits where idno ='$idno' and paymenttype=1 and isreverse = 0 group by refno"
                  . " UNION ALL "
                  . "select transactiondate,receiptno,amount from old_receipts where idno ='$idno') allrec order by transactiondate, receiptno");
          
          $totaldue = $schedulebal + $otherbalance;
          $reminder = session('remind');
          $pdf = \App::make('dompdf.wrapper');
          // $pdf->setPaper([0, 0, 336, 440], 'portrait');
          $pdf->loadView("print.printtvetsoa",compact('statuses','users','balances','trandate','schedules','others','otherbalance','totaldue','reminder','transactionreceipts'));
          return $pdf->stream();
    }
}
