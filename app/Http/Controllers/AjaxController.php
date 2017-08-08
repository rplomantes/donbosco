<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    //
    public function getid($varid){
        if(Request::ajax()){
        $user = \App\User::find($varid);
        $refno = $user->reference_number;
        $varrefno = strval($refno);
        $user->reference_number = $refno + 1;
        $user->update(); 
        
        $sy = \App\ctrSchoolYear::first();
            for($i=strlen($varrefno); $i< 3 ;$i++){
                $varrefno = "0" . $varrefno;    
            }
            
            $value = substr($sy->schoolyear,2,2) . $user->idref . $varrefno;
            $intval = 0;
            
            for($y=1; $y<= strlen($value); $y++){
                $sub = substr($value,$y);
                $intval = $intval + intval($sub);
            }
              //$intval = $intval%9;
              $varrefno = $value . strval($intval%9); 
             //   $varrefno = $value.$intval;
         // return $user->idref;  
        return $varrefno;
        }else{
        return "Invalid Request";   
           
        }
    }
    
    public function getlevel($vardepartment){
         if(Request::ajax()){
           if($vardepartment == "TVET") {
           $value = "<div class=\"col-md-12\">Course</div><div class=\"col-md-12\"><select name = \"course\" id=\"course\" class=\"form-control\" onchange = \"getplan(this.value)\"><option>Select Course</option>";
            $courses = DB::Select("select distinct course from ctr_subjects where department = 'TVET'");
           foreach($courses as $course){
            $value = $value . "<option value=\"" . $course->course ."\">" .$course->course . "</option>"; 
           }
           $value = $value . "</select></div>";
           
           return $value;
               
           }else{           
           $value = "<div class=\"col-md-12\">Level</div><div class=\"col-md-12\"><select name = \"level\" id=\"level\" class=\"form-control\" onchange = \"getplan(this.value)\"><option>Select Level</option>";
           $levels = \App\CtrLevel::where('department', $vardepartment )->get(); 
           foreach($levels as $level){
           $value = $value . "<option value=\"" . $level->level ."\">" .$level->level . "</option>"; 
           }
           $value = $value . "</select></div>";
           
           return $value;
           }
           
           }
           }
           
           function getplan($varlevelcourse, $vardepartment){
                if(Request::ajax()){
                   if($vardepartment == "Senior High School"){
                        $strands = DB::Select("select distinct strand from ctr_payment_schedules where level ='$varlevelcourse'");
              
                         $value = "<div class=\"col-md-12\">Specialization</div><div class=\"col-md-12\"><select name = \"strand\" id=\"strand\" class=\"form-control\" onchange = \"gettrackplan(this.value)\"><option>Select Track</option>";
                         foreach($strands as $strand){
                            $value = $value . "<option value=\"" . $strand->strand ."\">" .$strand->strand . "</option>"; 
                            }
                            $value = $value . "</select></div>";
                    return $value;
                }
                elseif($vardepartment == "TVET"){
                    $currentbatch = \App\ctrSchoolYear::where('department','TVET')->get();
                    //$batch=$currentbarch->period;
                    $value = "<div class=\"col-md-12\"><label>Select Batch</label>";
                    $value = $value . "<select name=\"batch\" id=\"batch\" class=\"form form-control\" onchange =\"gettvetplan(this.value)\">";
                    $value = $value . "<option value = \"none\">--Select Batch--</option>";
                    foreach($currentbatch as $cb){
                    $value = $value . "<option value = \"" . $cb->period . "\">". $cb->period . "</option>";
                    }
                   // $value = $value . "<option value =\"". ($batch-1) ."\">" . ($batch-1) . "</option>";
                    $value = $value . "</select></div>";  
                    return $value;
                }
                else{
                    if($varlevelcourse == "Grade 9" || $varlevelcourse == "Grade 10"){
                        //$strands = \App\CtrTrack::where('level',$varlevelcourse)->get();
                        $strands = DB::Select("select distinct strand from ctr_payment_schedules where level ='$varlevelcourse'");
                          $value = "<div class=\"col-md-12\">Specialization</div><div class=\"col-md-12\"><select name = \"strand\" id=\"strand\" class=\"form-control\" onchange = \"gettrackplan(this.value)\"><option>Select Track</option>";
                         foreach($strands as $strand){
                            $value = $value . "<option value=\"" . $strand->strand ."\">" .$strand->strand . "</option>"; 
                            }
                            $value = $value . "</select></div>";
                    return $value;
                    }
                     else{
                         
                    if($vardepartment == "TVET"){
                        $plans = DB::Select("select distinct plan from ctr_payment_schedules where  course = '$varlevelcourse'");
                        $value = "<div class=\"col-md-12\">Plan</div><div class=\"col-md-12\"><select name = \"plan\" id=\"plan\" class=\"form-control\" onchange = \"getdiscount()\"><option>Select Plan</option>";
                         foreach($plans as $plan){
                            $value = $value . "<option value=\"" . $plan->plan ."\">" .$plan->plan . "</option>"; 
                            }
                            $value = $value . "</select></div>";
                    return $value;
                        
                    }else{     
                    $plans = DB::Select("select distinct plan from ctr_payment_schedules where level = '$varlevelcourse'");
                     $value = "<div class=\"col-md-12\">Plan</div><div class=\"col-md-12\"><select name = \"plan\" id=\"plan\" class=\"form-control\" onchange = \"getdiscount()\"><option>Select Plan</option>";
                         foreach($plans as $plan){
                            $value = $value . "<option value=\"" . $plan->plan ."\">" .$plan->plan . "</option>"; 
                            }
                            $value = $value . "</select></div>";
                    return $value;
                     }
                }
                }
                
                }
           }
           
           
           function gettvetplan($batch,$course){
                $plans = DB::Select("select distinct plan from ctr_payment_schedules where department = 'TVET' AND course ='$course' AND period ='$batch'");
                     $value = "<div class=\"col-md-12\">Plan</div><div class=\"col-md-12\"><select name = \"plan\" id=\"plan\" class=\"form-control\" onchange = \"getdiscount()\"><option>Select Plan</option>";
                         foreach($plans as $plan){
                            $value = $value . "<option value=\"" . $plan->plan ."\">" .$plan->plan . "</option>"; 
                            }
                            $value = $value . "</select></div>";
                    return $value;
               
           }
         
            function gettrackplan(){
                  if(Request::ajax()){
                 
                   $plans = DB::Select("select distinct plan from ctr_payment_schedules where level = '".Input::get("level")."' and strand ='". Input::get("strand"). "'");
                     $value = "<div class=\"col-md-12\">Plan</div><div class=\"col-md-12\"><select name = \"plan\" id=\"plan\" class=\"form-control\" onchange = \"getdiscount()\"><option>Select Plan</option>";
                         foreach($plans as $plan){
                            $value = $value . "<option value=\"" . $plan->plan ."\">" .$plan->plan . "</option>"; 
                            }
                            $value = $value . "</select></div>";
                    return $value;
                
                      
                  }  
            }
            
            function getdiscount(){
                if(Request::ajax()){
                 $department = Input::get('department');
                 if($department=="TVET"){
                     /*
                    $currentbarch = \App\ctrSchoolYear::where('department','TVET')->first();
                    $batch=$currentbarch->period;
                    $value = "<div class=\"col-md-6\"><label>Select Batch</label>";
                    $value = $value . "<select name=\"abtch\" class=\"form form-control\">";
                    $value = $value . "<option value = \"$batch\" selected> $batch</option>";
                    $value = $value . "<option value =\"". ($batch-1) ."\">" . ($batch-1) . "</option>";
                    $value = $value . "</select></div>"; */       
                 $misc="";
                 $grad="";
                 $tf="";
                 $totalfee;
                 $plan = Input::get('plan');
                 $course=Input::get('course');
                 $batch=Input::get('batch');
                 $scheds = \App\CtrPaymentSchedule::where('department','TVET')->where('plan',$plan)->where('course',$course)->where('period',$batch)->get();
                 foreach($scheds as $sched){
                     if($sched->description=="Tuition Fee"){
                         $tf = $sched->amount;
                     }
                     elseif($sched->description=="Miscellaneous"){
                        $misc = $sched->amount;
                     } elseif($sched->description=="Grad Fee") {
                         $grad = $sched->amount;
                     } else {
                         $tf=$sched->amount;
                     }
                 }
                 $totalfee=$tf+$grad+$misc;
                 
                 $value="<table border = \"0\"  ><tr><td>Particular</td>";
                 $value=$value."<td> Amount </td>";
                 $value=$value."<td>Discount </td>";
                 $value=$value."<td>Sponsor </td>";
                 $value=$value."<td>Trainee </td></tr>";
                 $value=$value."<tr><td>Tution Fee</td>";
                 $value=$value."<td> <input style=\"text-align:right\" id=\"tuitionfee\"  value=\"$tf\"name = \"tuitionfee\" type=\"text\" readonly class=\"form form-control\"></td>";
                 $value=$value."<td><select id=\"discount\" name=\"discount\" class=\"form form-control\" onchange=\"computetvet()\"> ";
                 $value=$value."<option value=\"0\">None</option>";
                 $value=$value."<option value=\"100\">100 Percent</option>";
                // $value=$value."<option value=\"10\">10 Percent</option>";
                // $value=$value."<option value=\"15\">15 Percent</option>";
                // $value=$value."<option value=\"30\">30 Percent</option>";
                // $value=$value."<option value=\"50\">50 Percent</option>";
                // $value=$value."<option value=\"70\">70 Percent</option>";
                // $value=$value."<option value=\"75\">75 Percent</option>";
                 $value=$value."<option value=\"90\">90 Percent</option>";
                 $value=$value."<option value=\"85\">85 Percent</option>";
                 $value=$value."<option value=\"70\">70 Percent</option>";
                 $value=$value."<option value=\"50\">50 Percent</option>";
                 $value=$value."<option value=\"30\">30 Percent</option>";
                 $value=$value."<option value=\"25\">25 Percent</option></select></td>";
                 $value=$value."<td align=\"center\"><input name=\"paidby_tuitionfee\"  id =\"tuitionfee_sponsor\" value=\"sponsor\" type=\"radio\" onclick=\"computetvet()\"></td>";
                 $value=$value."<td align=\"center\"><input name=\"paidby_tuitionfee\" id =\"tuitionfee_trainee\" checked value=\"trainee\" type=\"radio\" onclick=\"computetvet()\"></td></tr>";
                 
                 $value=$value."<tr><td>Miscellaneous</td>";
                 $value=$value."<td> <input style=\"text-align:right\" id=\"misc\"  value=\"$misc\" name = \"misc\" type=\"text\" readonly class=\"form form-control\"></td>";
                 $value=$value."<td> ";
                 $value=$value."</td>";
                 $value=$value."<td align=\"center\"><input id=\"misc_sponsor\"  name=\"paidby_misc\" value=\"sponsor\" type=\"radio\" onclick=\"computetvet()\"></td>";
                 $value=$value."<td align=\"center\"><input id=\"misc_trainee\" name=\"paidby_misc\" checked value=\"trainee\" type=\"radio\" onclick=\"computetvet()\"></td></tr>";
                 
                 $value=$value."<tr><td>Graduation Fee</td>";
                 $value=$value."<td> <input style=\"text-align:right\" id=\"gradfee\"  value=\"$grad\"name = \"gradfee\" type=\"text\" readonly class=\"form form-control\"></td>";
                 $value=$value."<td> ";
                 $value=$value."</td>";
                 $value=$value."<td align=\"center\"><input name=\"paidby_gradfee\" id=\"gradfee_sponsor\" value=\"sponsor\" type=\"radio\" onclick=\"computetvet()\"></td>";
                 $value=$value."<td align=\"center\"><input name=\"paidby_gradfee\" id=\"gradfee_trainee\" checked value=\"trainee\" type=\"radio\" onclick=\"computetvet()\"></td></tr>";
                 $value=$value."<tr><td colspan=\"3\">Total Trainee's Contribution</td>";
                 $value=$value."<td colspan=\"2\"><input type=\"text\" style=\"text-align:right\" placeholder=\"0.00\" value=\"$totalfee\" name=\"contribution\" id=\"contribution\" class=\"form form-control\"></td></tr>";
                 $value=$value."<tr><td rowspan=\"2\" colspan=\"5\"><br><input type=\"submit\" value=\"Process TVET Assessment\" class=\"btn btn-primary form form-control\"></td></tr></table>";
                 
                     }else{
                 $discounts = \App\CtrDiscount::orderby('discountcode')->get();
                    $value = "<div class=\"col-md-12\">Discount</div><div class=\"col-md-12\"><select name = \"discount\" id=\"discount\" class=\"form-control\" onchange = \"compute()\">"
                            . "<option value=\"\">Select Discount</option> <option value=\"none\">None</option>";
                         foreach($discounts as $discount){
                            $value = $value . "<option value=\"" . $discount->discountcode ."\">" .$discount->description . "</option>"; 
                            }
                            $value = $value . "</select></div>";
                 }  
                     return $value;
                    
                }
            }
                function getsearch($varsearch){
                    if(Request::ajax()){
                    $find = strtolower($varsearch);
                    $searches = DB::Select("Select * From users where accesslevel = '0' AND (lastname like '$varsearch%' OR lcase(lastname) like '$find%' OR "
                            . "firstname like '$varsearch%' OR idno = '$varsearch') Order by lastname, firstname");
                    $value = "<table class=\"table table-striped\"><thead>
            <tr><th>Student Number</th><th>Student Name</th><th>Gender</th><th>Assessment</th><th>Student Info</th><th>Student Grade</th></tr>        
            </thead><tbody>";
                    foreach($searches as $search){
                        $value = $value . "<tr><td>" .$search->idno . "</td><td>". $search->lastname . ", " .
                                $search->firstname . " " . $search->middlename . " " . $search->extensionname .
                                "</td><td>" . $search->gender . "</td><td><a href = '/registrar/evaluate/".$search->idno."'>Assess</a></td><td><a href = '/studentinfokto12/".$search->idno."'>Viewn Info</a></td><td><a href = '/seegrade/".$search->idno."'>View Grades</a></td>";
                    }
                      
                    $value = $value . "</tbody>
            </table>"; 
                        
                    return $value; 
                    }
                }
                
                 function getsearchcashier($varsearch){
                    if(Request::ajax()){
			$find = strtolower($varsearch);
                    $searches = DB::Select("Select * From users where accesslevel = '0' AND (lastname like '$varsearch%' OR lcase(lastname) like '$find%' OR 
                           firstname like '$varsearch%' OR idno = '$varsearch') Order by lastname, firstname");
                    $value = "<table class=\"table table-striped\"><thead>
            <tr><th>Student Number</th><th>Student Name</th><th>Gender</th><th>Info</th><th>View</th></tr>        
            </thead><tbody>";
                    foreach($searches as $search){
                        $value = $value . "<tr><td>" .$search->idno . "</td><td>". $search->lastname . ", " .
                                $search->firstname . " " . $search->middlename . " " . $search->extensionname .
                                "</td><td>" . $search->gender . "</td><td><a href = '/studentinfo/".$search->idno."'>view</a></td><td><a href = '/cashier/".$search->idno."'>view</a></td>";
                    }
                      
                    $value = $value . "</tbody>
            </table>"; 
                        
                    return $value; 
                    }
                }
                
                function getsearchaccounting($varsearch){
                    if(Request::ajax()){
		    $find = strtolower($varsearch);
                    $searches = DB::Select("Select * From users where accesslevel = '0' AND (lastname like '$varsearch%' OR lcase(lastname) like '$find%' OR 
                           firstname like '$varsearch%' OR idno = '$varsearch') Order by lastname, firstname");
                    $value = "<table class=\"table table-striped\"><thead>
            <tr><th>Student Number</th><th>Student Name</th><th>Gender</th><th>Info</th><th>View</th></tr>        
            </thead><tbody>";
                    foreach($searches as $search){
                        $value = $value . "<tr><td>" .$search->idno . "</td><td>". $search->lastname . ", " .
                                $search->firstname . " " . $search->middlename . " " . $search->extensionname .
                                "</td><td>" . $search->gender . "</td><td><a href = '/studentinfo/".$search->idno."'>view</a><td></td><td><a href = '/accounting/".$search->idno."'>view</a><td>";
                    }
                      
                    $value = $value . "</tbody>
            </table>"; 
                        
                    return $value; 
                    }
                }
                
                function compute(){
                $otherdiscountname = "None";
                $otherdiscountrate = 0;
                $advance = 0;
                
                $otherdiscounts=  \App\CtrDiscount::where('discountcode', Input::get('discount'))->first();
                if(!is_null($otherdiscounts)){
                $otherdiscountname = $otherdiscounts->description;
                $otherdiscountrate = ($otherdiscounts->tuitionfee/100);
                }
                
                $department = Input::get("department");
                $level = Input::get("level");
                $strand = Input::get("strand");
                $course = Input::get("course");
                $plan = Input::get("plan");
                $id =Input::get('id');
                
                $currentpreiod = \App\ctrSchoolYear::where("department", $department)->first();
                $advances = DB::Select("select * from advance_payments where idno='$id' and status = '0'");
                    foreach($advances as $adv){
                        $advance = $advance + $adv->amount;
                    }
             
                
                if($department == "TVET"){
                     $schedules = DB::Select("select sum(amount) as amount, sum(discount) as discount, receipt_details, plan, level   from ctr_payment_schedules
                             where  course = '$course' and plan = '$plan' Group by receipt_details, plan, level ");

                }
                elseif($department == "Senior High School"){
                    $schedules = DB::Select("select sum(amount) as amount, sum(discount) as discount, receipt_details, plan, level   from ctr_payment_schedules
                             where strand = '$strand' and level = '$level' and plan = '$plan' Group by receipt_details, plan, level ");
                }
                else{
                    //$matchfields= ['level' => $level,'plan' => $plan];
                   
                    
                    if($level=='Grade 9' || $level=='Grade 10'){
                      $schedules = DB::Select("select sum(amount) as amount, sum(discount) as discount, receipt_details, plan, level   from ctr_payment_schedules
                             where strand = '$strand' and level = '$level' and plan = '$plan' Group by receipt_details, plan, level ");
                    //$   
                    }else{
                    $schedules = DB::Select("select sum(amount) as amount, sum(discount) as discount, receipt_details, plan, level  from ctr_payment_schedules
                             where  level = '$level' and plan = '$plan' Group by receipt_details, plan, level");
                    //$schedules = \App\CtrPaymentSchedule::where($matchfields)->get();
                    }  }
                     $total=0;
                    $discount = 0;
                    $otherdiscount = 0;
                    
                    $request = "<h5>Main Fees</h5><table class = \"table table-bordered\"><tr><td>Description</td><td>Amount</td><tr>";
                    foreach($schedules as $schedule){
                    if(stristr($schedule->receipt_details, "Tuition")){
                    $otherdiscount = $otherdiscount + (($schedule->amount-$schedule->discount) * $otherdiscountrate);     
                    } 
                    $discount = $discount + $schedule->discount;
                    $total = $total + $schedule->amount; 
                    
                    //$request = $request ."<tr><td>". $schedule->receipt_details."</td><td align=\"right\">" . number_format($schedule->amount,2)."</td></tr>";    
                    }
                    $request = $request . "<tr><td>Total Fee</td><td align=\"right\"><strong style=\"color:black\">". number_format($total,2)."</strong></td></tr>";
                    $request = $request . "<tr><td> Less: Plan Discount</td><td align=\"right\"><strong style=\"color:red\">(". number_format($discount,2).")</strong></td></tr>";
                    $request = $request . "<tr><td>Other Discount: $otherdiscountname</td><td align=\"right\"><strong style=\"color:red\">(". number_format($otherdiscount,2).")</strong></td></tr>";
                    $request = $request . "<tr><td>Advance Payment</td><td align=\"right\"><strong style=\"color:red\">(". number_format($advance,2).")</strong></td></tr>";
                    $request = $request . "<tr><td> Total </td><td align=\"right\"><strong style=\"color:black\">". number_format($total-$discount-$otherdiscount-$advance,2)."</strong></td></tr>";
                    $request = $request . "</table>";
                    if($strand==""){
                    $books = \App\CtrBook::where('level',$level)->get();    
                    }else{
                    $books = \App\CtrBook::where('level',$level)->where('strand',$strand)->get();
                    }
                    if(count($books)>0){
                        $request = $request . "<h5>Books</h5><table class=\"table table-bordered\"><tr><td><div id=\"check_uncheck\"><p  style=\"cursor:pointer\"id=\"select_all\" onclick=\"unselect_all()\">Uncheck All</></div></td><td>Amount</td>";
                            foreach($books as $book){
                            $request = $request . "<tr><td><input type=\"checkbox\" class=\"books\" name=\"books[]\" value=\"".$book->id."\" checked=\"checked\"> ".$book->subsidiary."</td>";
                            $request = $request . "<td align=\"right\">".number_format($book->amount,2)."</td></tr>";        
                            }
                        $request = $request . "</table>";
                    }
                        
                     $request = $request."<div class=\"col-md-12\"><input id=\"submit_button\" type=\"submit\" value=\"Process Assessment\" class=\"form-control btn btn-warning\">";
                    
                 return $request;
                  
                }
            
            function getpaymenttype($ptype){
                if(Request::ajax()){
                $data="";
                    if($ptype == "1"){
                //$data = "<table class=\"table table-responsive\"  style=\"background-color: #C6C6FF\"><tr><td>Amount Received</td><td>
                 //       <input style =\"text-align: right\" type=\"text\" name=\"receive\" onkeypress=\"validate(event)\" class=\"form form-control\">
                 //       </td></tr></table>";
                      $data="";  
                } else {
                    $data = "<table class=\"table table-responsive\"  style=\"background-color: #C6C6FF\"><tr><td>Check Number</td><td>
                        <input  type=\"text\" name=\"check_number\" id=\"check_number\" onkeydown = \"nosubmit(event,'bank_branch')\" class=\"form form-control\">
                        </td></tr>";
                    $data = $data . "<tr><td>Bank/Branch</td><td>
                        <input type=\"text\" name=\"bank_branch\"  id=\"bank_branch\"  onkeydown = \"nosubmit(event,'receive')\"  class=\"form form-control\">
                        </td></tr>";
                   /* $data = $data . "<tr><td>Amount</td><td>
                        <input style =\"text-align: right\" type=\"text\" name=\"amount\" onkeypress=\"validate(event)\" class=\"form form-control\">
                        </td></tr></table>";*/
                }
                
                return $data;
            }}
            
            function getparticular($group, $particular){
                if(Request::ajax()){
                $particulars = \App\CtrOtherPayment::where("accounttype",$group)->get();
                $data = "
                        <select class=\"form-control\" name=\"".$particular."\">";
                    foreach($particulars as $part){
                    $data = $data . "<option value = '" . $part->particular . "'>" . $part->particular. "</option>";  
                    }
             
               $data = $data."</select>";
                return $data;    
                }
            
            }
            
            function getprevious($idno, $schoolyear){
                if(Request::ajax()){
             
                $ledgers = DB::Select("select sum(amount) as amount, sum(plandiscount) as plandiscount,  sum(otherdiscount) as otherdiscount, "
                        . "sum(debitmemo) as debitmemo, sum(payment) as payment, receipt_details from ledgers  where schoolyear = '".$schoolyear."' and "
                        . "idno = '".$idno."' group by receipt_details"); 
                //$matchfields=['idno'=>$idno, 'schoolyear'=>$schoolyear, 'paymenttype'=>'1'];
                //$debits = DB::Select("select dedits.*  from dedits, credits where dedits.refno = credits.refno and "
                //        . " credits.schoolyear = '". $schoolyear ."' and dedits.paymenttype = '1'");
               
                
                $data="";
                $data = $data . "<h5>Account Details</h5>";
                $data = $data . "<table class=\"table table-striped\">";
                $data = $data . "<tr><td>Description</td><td align=\"right\">Amount</td><td align=\"right\">Discount</td><td align=\"right\">DM</td><td align=\"right\">Payment</td><td align=\"right\">Balance</td></tr>";
                $totalamount = 0;
                $totaldiscount = 0;
                $totaldebitmemo = 0;
                $totalpayment = 0;
                
                if(count($ledgers) > 0){
                foreach($ledgers as $ledger){
              
                $totalamount = $totalamount + $ledger->amount;
                $totaldiscount = $totaldiscount + $ledger->plandiscount + $ledger->otherdiscount;
                $totaldebitmemo = $totaldebitmemo + $ledger->debitmemo;
                $totalpayment = $totalpayment + $ledger->payment;
               
                 $data = $data . "<tr><td>". $ledger->receipt_details ."</td><td align=\"right\">". number_format($ledger->amount,2). "</td><td align=\"right\">". number_format($ledger->plandiscount+$ledger->otherdiscount,2)."</td>";
                 $data = $data .  "<td align=\"right\">".number_format($ledger->debitmemo,2)."</td><td align=\"right\" style=\"color:red\">".number_format($ledger->payment,2)."</td>";
                 $data = $data . "<td align=\"right\">" .number_format($ledger->amount-$ledger->debitmemo-$ledger->plandiscount-$ledger->otherdiscount-$ledger->payment,2). "</td></tr>";
                }}
                 $data = $data . "<tr><td>Total</td><td align=\"right\">". number_format($totalamount,2)."</td>";
                 $data = $data . "<td align=\"right\">".number_format($totaldiscount,2)."</td>"; 
                 $data = $data . "<td align=\"right\">".number_format($totaldebitmemo,2)."</td>";
                 $data = $data . "<td align=\"right\" style=\"color:red\">".number_format($totalpayment,2)."</td>";
                 $data = $data . "<td align=\"right\"><strong>". number_format($totalamount-$totaldiscount-$totaldebitmemo-$totalpayment,2)."</strong></td></tr>";
                 $data = $data . "</table>";
                 
                 $data = $data . "<h5>Payment History<h5>"; 
                 $data = $data . "<table class=\"table table-striped\"><tr><td>Date</td><td>Ref Number</td><td>OR Number</td><td align=\"right\">Amount</td><td>Payment Type</td><td>Details</td><td>Status</td></tr>";
               
                 $credits = DB::Select("select distinct refno from credits where idno = '". $idno."' and schoolyear = '". $schoolyear ."'");
                    foreach($credits as $credit){
                         $debits = DB::Select("select * from dedits where refno = '" . $credit->refno ."'");   
                            if(count($debits)>0){
                            foreach($debits as $debit){
                                $data = $data . "<tr><td>" . $debit->transactiondate ."</td><td>" . $debit->refno ."</td><td>" . $debit->receiptno . "</td><td align=\"right\">".number_format($debit->amount + $debit->checkamount,2)."</td><td>";
                                     if($debit->paymenttype=='1'){
                                        $data = $data . "Cash/Check";
                                    }
                                    elseif($debit->paymenttype=='3'){
                                        $data = $data ."DEBIT MEMO";
                                    }
                                    $data = $data . "</td><td><a href='".url('/viewreceipt',array($debit->refno,$idno))."'>View</a></td>";
                                    $data = $data ."<td>";
                                    if($debit->isreverse=="0"){
                                    $data = $data . "Ok";
                                    }else{
                                    $data = $data . "Cancelled";
                                    }  
                                    $data = $data . "</td>";
                  
                                    $data = $data ."</tr>";
                            } }}
                                        $data = $data ."</table>";
                         
                         
                         
                         
                 return $data;
                }
            }
    
//VINCENT 09/15/16
            function studentlist($level){
        if(Request::ajax()){
         if($level == "Grade 9" || $level=="Grade 10" || $level=="Grade 11" || $level=="Grade 12"){
         $strands = DB::Select("select distinct strand from ctr_payment_schedules where level = '$level'");
         $data = "<div class=\"form form-group\">";
        
         $data=$data. "<Select name =\"strand\" id=\"strand\" class=\"form form-control\" onchange=\"getstrand(this.value)\" >";
          $data=$data. "<option>Select Strand/Shop</option>";
         foreach($strands as $strand){
          $data = $data . "<option value=\"". $strand->strand . "\">" . $strand->strand . "</option>";       
               }
         $data = $data . "</select></div>"; 
          return $data;
         }  else{
            $sections = DB::Select("select distinct section from statuses where level = '$level' and section != '' ");
            $data = "<div class=\"form form-group\">";

            $data=$data. "<Select name =\"section\" id=\"section\" class=\"form form-control\" onchange=\"showstudents()\" >";
             $data=$data. "<option>Select Section</option>";
             $data = $data . "<option value= 'All'>All</option>";  
            foreach($sections as $section){
             $data = $data . "<option value=\"". $section->section . "\">" . $section->section . "</option>";       
                  }
            $data = $data . "</select></div>"; 
            
            return $data;
         }
          
        }
        
    }
//VINCENT 09/15/16
    function strand($strand, $level){
        if(Request::ajax()){
       /* 
        $lists = DB::Select("select users.idno, users.lastname, users.firstname, users.extensionname, users.middlename, "
                 . "statuses.level, statuses.strand from users, statuses where users.idno = statuses.idno "
                 . "and statuses.level = '". $level. "' and statuses.strand='". $strand ."' order by users.lastname, users.firstname");
         $data = "<h3>$level</h3><table class=\"table table-stripped\"><tr><td>Student Id</td><td>Name</td></tr>";
         foreach($lists as $list){
         $data = $data . "<tr><td>".$list->idno . "</td><td>". $list->lastname.", ".$list->firstname. " " . $list->middlename . " </td></tr>";
         
         }
         $data = $data . "</table>";
        ----------------------------
        $lists = DB::Select("select users.idno, users.lastname, users.firstname, users.extensionname, users.middlename, "
                 . "statuses.level, statuses.strand, student_infos.fname, student_infos.fmobile, student_infos.mname, student_infos.mmobile from users, statuses, student_infos  where users.idno = statuses.idno "
                 . "and statuses.strand = '".$strand."' and statuses.level = '". $level. "'  and statuses.status='2' and statuses.idno = student_infos.idno order by users.lastname, users.firstname");
         $data = "<h3>$level</h3><table class=\"table table-stripped\"><tr><td>Id No</td><td>Name</td><td>Father</td><td>Contact No</td><td>Mother</td><td>Contact No.</td></tr>";
         foreach($lists as $list){
         $data = $data . "<tr><td>".$list->idno . "</td><td>". $list->lastname.", ".$list->firstname. " " . $list->middlename . " </td><td>".$list->fname."</td>"
                 . "<td>".$list->fmobile."</td><td>".$list->mname."</td><td>".$list->mmobile."</td></tr>";
         
         }
         $data = $data . "</table>";
        
         */
            $sections = DB::Select("select distinct section from statuses where level = '$level' and strand='$strand' and section != '' ");
            $data = "<div class=\"form form-group\">";

            $data=$data. "<Select name =\"section\" id=\"section\" class=\"form form-control\" onchange=\"showstudents()\" >";
             $data=$data. "<option>Select Section</option>";
             $data = $data . "<option value= 'All'>All</option>";  
            foreach($sections as $section){
             $data = $data . "<option value=\"". $section->section . "\">" . $section->section . "</option>";       
                  }
            $data = $data . "</select></div>";  
         
            
         return $data; 
        } 
        
    }
//VINCENT 09/15/16
    function studentContact($level,$section){
        $schoolyear = \App\CtrSchoolYear::first();
        if(Request::ajax()){
            $strand = Input::get("strand");
        if ($section == "All"){    
                $sections = DB::Select("Select distinct section as section from statuses where level = '$level' and status = 2");
        }
        else{
            $sections = DB::Select("Select distinct section as section from statuses where level = '$level' and section = '$section'");
        }
        $data = "";
        $data = $data."<h3 class='no-print'>".$level."</h3>";
        foreach($sections as $section){
            $lists = DB::Select("select users.idno, users.lastname, users.firstname, users.extensionname, users.middlename, "
                     . "statuses.level, statuses.strand,birthDate ,student_infos.phone1,student_infos.phone2,student_infos.fname, student_infos.fmobile,student_infos.flandline,student_infos.mlandline, student_infos.mname, student_infos.mmobile from users, statuses, student_infos  where users.idno = statuses.idno "
                     . "and statuses.level = '$level' and statuses.section = '$section->section'  and statuses.status='2' and statuses.idno = student_infos.idno order by users.lastname, users.firstname");
            $data = $data."<h3 class='no-print'>".$section->section."</h3>";
            $data = $data."<table style='width:100%'><tr class='headers'><td style='text-align:center;'>";
            $data = $data."<div style='text-align: right;padding-left: 0px;vertical-align: top;position: relative;width: 0px;right: 80px;display:inline-block' width='55px'>";
            $data = $data."<img src='".asset('images/logo.png')."'  style='display: inline-block;width:90px'>";
            $data = $data."</div>";            
            $data = $data."<table border='0' cellpadding='0' cellspacing='0' style='text-align:center;margin-left: auto;margin-right: auto;display:inline-block'>";

            $data = $data."<tr>";

            $data = $data."<td style='padding-left: 0px;'>";
            $data = $data."<span style='font-size:12pt; font-weight: bold'>DON BOSCO TECHNICAL INSTITUTE</span>";
            $data = $data."</td>";
            $data = $data."</tr>";
            $data = $data."<tr><td style='font-size:9pt;text-align: center;padding-left: 0px;'>Chino Roces Ave., Makati City </td></tr>";
            $data = $data."<tr><td style='font-size:9pt;text-align: center;padding-left: 0px;'>PAASCU Accredited</td></tr>";
            $data = $data."<tr><td style='font-size:9pt;text-align: center;padding-left: 0px;'>School Year ".$schoolyear->schoolyear." - ".(intval($schoolyear->schoolyear)+1)."</td></tr>";
            $data = $data."<tr><td style='font-size:9pt;padding-left: 0px;'>&nbsp; </td></tr>";
            $data = $data."<tr><td><span style='font-size:5px'></td></tr></table>";
            
            $data = $data."</tr></table>";
            $data = $data."<div>STUDENT CONTACT</div>";
            $data = $data."</td></thead><tr><td>";
            $data = $data."<table width='100%' ><tr class='headers'><td>";
            $data = $data."<div>Level: ".$level."</div>";
            $data = $data."<div>Section: ".$section->section."</div>";
            $data = $data."</td></tr></table>";
            $data = $data."</td></tr><tr><td>";
            
            $data = $data."<table class=\"table table-stripped\" style=\"page-break-after:always\"><thead><td>Id No</td><td>Name</td><td width='100px'>Birth Date</td><td>Contact No</td><td>Father</td><td>Contact No</td><td>Mother</td><td>Contact No.</td></thead>";
            foreach($lists as $list){
            $data = $data . "<tr><td>".$list->idno . "</td><td>". $list->lastname.", ".$list->firstname. " " . $list->middlename . " </td><td>";
            $data = $data . " $list->birthDate</td><td>";
            $data = $data.$list->phone1;
            if($list->phone1 != "" && $list->phone2 != ""){
                $data = $data ." / ";
            }
            $data = $data .$list->phone2."</td>";
            
            $data = $data ."<td>".$list->fname."</td><td>";
            $data = $data.$list->fmobile;
            if($list->fmobile != "" && $list->flandline != ""){
                $data = $data ." / ";
            }
            $data = $data .$list->flandline."</td>";
                        
            $data = $data ."<td>".$list->mname."</td><td>";
            
            $data = $data .$list->mmobile;
            if($list->mmobile != "" && $list->mlandline != ""){
                $data = $data ." / ";
            }
            $data = $data .$list->mlandline."</td></tr>";
            }
            $data = $data . "</table>";             
            $data = $data."<td><tr></table>";
            $data = $data."<div style='page-break-after: auto'>";
            //$data = $data."<div>".$section->section."</div>";
        }
           return $data; 
            
        }
        
    }
    
     function myDeposit(){
        if(Request::ajax()){  
            $idno = Input::get('idno');
            $bank = Input::get('bank');
            $deposittype = Input::get('deposittype');
            $amount = Input::get('amount');
            $transactiondate=Input::get('transactiondate');
            
            
            $deposit_slip = new \App\DepositSlip;
            $deposit_slip->transactiondate = $transactiondate;
            $deposit_slip->bank=$bank;
            $deposit_slip->deposittype=$deposittype;
            $deposit_slip->postedby=$idno;
            $deposit_slip->amount = $amount;
            $deposit_slip->save();
            return "true";
        }    
     }
        function removeslip($refid){
        if(Request::ajax()){
            \App\DepositSlip::where('id',$refid)->delete();
            
             return "true";
        }
        }
        
        function getstudentlist($level){
            if(Request::ajax()){
                 $sy = \App\CtrSchoolYear::first()->schoolyear; 
                   
                    $studentnames = DB::Select("select statuses.id, statuses.idno, users.lastname, "
                        . "users.firstname, users.middlename, statuses.section  from statuses, users where statuses.idno = "
                        . "users.idno and statuses.level = '$level' and statuses.schoolyear='$sy' and statuses.strand = '" . Input::get("strand") ."'  and statuses.status = '2' order by users.lastname, users.firstname, users.middlename");
               
                
                $data = "";
                $data = $data . "<table class=\"table table-stripped\"><tr><td>ID No</td><td>Name</td><td>Section</td></tr>";
                    foreach($studentnames as $studentname){
                        $data = $data . "<tr><td>".$studentname->idno."</td><td><span style=\"cursor:pointer\"onclick=\"setsection('" . $studentname->id . "')\">".$studentname->lastname . ", " . $studentname->firstname . " " .$studentname->middlename . "</span></td><td>" . $studentname->section . "</td></tr>"; 
                    }
                $data = $data."</table>";
                
                return $data;
            }
        }
        
        function getsection($level){
            if(Request::ajax()){
                $strand = Input::get("strand");
                $sections = DB::Select("select  * from ctr_sections where level = '$level' and strand = '$strand'");
                   $data = "";
                   $data = $data . "<div class=\"col-md-6\"><label for=\"section\">Select Section</label><select id=\"section\" onchange=\"callsection()\" class=\"form form-control\">";
                 $data = $data . "<option>--Select--</option>";
                   foreach($sections as $section){
                      $data = $data . "<option value= '". $section->section ."'>" .$section->section . "</option>";  
                    }
                   $data = $data."</select></div>";
                return $data;   
                //return "roy";
            }
        }
        
        function getsection1($level){
            if(Request::ajax()){
                /*
              if($level=="Grade 9" || $level=="Grade 10" || $level=="Grade 11" || $level=="Grade 12"){
                  $strands = DB::Select("select distinct strand from ctr_sections where level = '$level'");
                  $data="";
                  $data = $data . "<label for=\"strand\">Select Strand/Shop</label><select id=\"strand\"  onchange=\"getsectiontrack(this.value)\" name=\"strand\" class=\"form form-control\">";
                  $data = $data . "<option>--Select--</option>";
                  foreach($strands as $strand){
                      $data = $data . "<option value= '". $strand->strand ."'>" .$strand->strand . "</option>";  
                    }
                   $data = $data."</select>";
              }  else {*/
                $sections = DB::Select("select  distinct section from ctr_sections where level = '$level'");
                   $data = "";
                   $data = $data . "<label for=\"section\">Section</label><select id=\"section\" name=\"section\" class=\"form form-control\" onchange= \"getstudents(this.value)\">";
                   $data = $data . "<option>--Select--</option>"; 
                   $data = $data . "<option value=\"All\">All</option>"; 
                   foreach($sections as $section){
                      $data = $data . "<option value= '". $section->section ."'>" .$section->section . "</option>";  
                    }
                   $data = $data."</select>";
              //}
                return $data;   
                //return "roy";
            }
        }
        
        function getsectionstrand($level,$strand){
            if(Request::ajax()){
             
                $sections = DB::Select("select  * from ctr_sections where level = '$level' and strand='$strand'");
                   $data = "";
                   $data = $data . "<label for=\"section\">Select Section</label><select id=\"section\"  class=\"form form-control\">";
                    foreach($sections as $section){
                      $data = $data . "<option value= '". $section->section ."'>" .$section->section . "</option>";  
                    }
                   $data = $data."</select>";
              
                return $data;   
                //return "roy";
            }
        }
        
        function getsectionlist($level,$section){
            if(Request::ajax()){
                 $ad = \App\CtrSection::where('level',$level)->where('section',$section)->where('strand',Input::get('strand'))->first();
                 $adviser = $ad->adviser;
		$sy = \App\CtrSchoolYear::first()->schoolyear;
                $studentnames = DB::Select("select statuses.id, statuses.idno, users.lastname, "
                        . "users.firstname, users.middlename, statuses.section from statuses, users where statuses.idno = "
                        . "users.idno and statuses.level = '$level' AND schoolyear = '$sy'  AND statuses.section = '$section' and strand = '" . Input::get("strand") . "' order by users.gender, users.lastname, users.firstname, users.middlename");
                $cn=1;
                $data = "<div class=\"col-md-6\"><label for=\"adviser\">Adviser</label><input type=\"text\" id=\"adviser\" class=\"form form-control\" value=\"" . $adviser . "\" onkeyup = \"updateadviser(this.value,'" . $ad->id . "')\"></div>";
                $data = $data . "<table class=\"table table-stripped\"><tr><td>ID No</td><td>CN</td><td>Name</td><td>Section</td></tr>";
                    foreach($studentnames as $studentname){
                        $data = $data . "<tr><td>".$studentname->idno."</td><td>" . $cn++ . "</td><td><span style=\"cursor:pointer\" onclick=\"rmsection('" . $studentname->id . "')\">".$studentname->lastname . ", " . $studentname->firstname . " " .$studentname->middlename . "</span></td><td>" . $studentname->section . "</td></tr>"; 
                    }
                $data = $data."</table>";
                $data = $data . "<a href = \"". url('/printsection', array($level,$section,Input::get('strand')))."\" class =\"btn btn-primary\" target = \"_blank\"> Print Section</a>";
                return $data;
                //return "roy";
            }
        }
        
        function setsection($id, $section){
            if(Request::ajax()){
            $updatesection = \App\Status::find($id);
            $updatesection->section = $section;
            $updatesection->update();
            return "true";
            }
        }

        function rmsection($id){
            if(Request::ajax()){
            $updatesection = \App\Status::find($id);
            $updatesection->section = "";
            $updatesection->update();
            return "true";
            }
        }
        
        function getstrand($level){
            if(Request::ajax()){
                $strands = DB::Select("select distinct strand from ctr_sections where level = '$level'");
                $data = "<div class=\"form form-group\"><label for=\"strand\">Select Shop/Strand</label>";
                $data=$data. "<Select name =\"strand\" id=\"strand\" class=\"form form-control\" onchange=\"getstrandall(this.value)\" >";
                $data=$data. "<option>--Select--</option>";
                    foreach($strands as $strand){
                        $data = $data . "<option value=\"". $strand->strand . "\">" . $strand->strand . "</option>";       
                    }
                $data = $data . "</select></div>"; 
                return $data;
            }
        }
        
        function updateadviser($id, $value){
            $adviser = \App\CtrSection::find($id);
            $adviser->adviser = $value;
            $adviser->update();
            
            return true;
        }
        
        function getsoasummary($level,$strand,$section,$trandate){
       if($strand=="none"){
           $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, "
                . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
                . " statuses.level = '$level' and statuses.section='$section' and ledgers.duedate <= '$trandate' "
                . " group by statuses.idno, users.lastname, users.firstname, users.middlename order by users.lastname, users.firstname");    

       }   else{  
        $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, "
                . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
                . " statuses.level = '$level' and statuses.strand='$strand' and statuses.section='$section' and ledgers.duedate <= '$trandate' "
                . " group by statuses.idno, users.lastname, users.firstname, users.middlename order by users.lastname, users.firstname");    
       }
        $data = "";
        $data = $data . "<table class = \"table table-stripped\"><tr><td>Student No</td><td>Name</td><td>Balance</td><td></td></tr>";
        foreach($soasummary as $soa){
        if($soa->amount > 0){    
        $data = $data . "<tr><td>" . $soa->idno . "</td><td>"
                . $soa->lastname . ", "
                . $soa->firstname . " " . $soa->middlename. "</td>"
                . "<td align=\"right\">" . number_format($soa->amount,2) . "</td>"
                . "<td><a href=\"/printsoa/". $soa->idno. "/".$trandate ."\">Print</a></td></tr>";
        }}
        $data = $data."</table>";
        return $data;
           // return $level.$strand.$section.$trandate;
        }
        
        function displaygrade(){
            $data = "";
            $currSy = \App\CtrSchoolYear::first()->schoolyear;
            $sy = Input::get('sy');
            if($currSy == $sy){
                $status = \App\Status::where('idno',Input::get('idno'))->where('schoolyear',$sy)->orderBy('id','DESC')->first();
            }else{
                $status = \App\StatusHistory::where('idno',Input::get('idno'))->where('schoolyear',$sy)->orderBy('id','DESC')->first();
            }
            
            $academics = \App\Grade::where('idno',Input::get('idno'))->where('schoolyear',Input::get('sy'))->where('subjecttype','0')->orderBy('sortto')->get();
           if(count($academics)>0){
           
            $data = $data . "<table class=\"table table-stripped\"><tr><td><span class=\"subjecttitle\">Academic Subject</span></td><td>1</td><td>2</td><td>3</td><td>4</td><td>Final</td><td>Remarks</td></tr>";
            foreach($academics as $grade){
            $data = $data . "<tr><td width=\"50%\">".$grade->subjectname."</td><td>".round($grade->first_grading)."</td><td>" . round($grade->second_grading) . ""
                    . "</td><td>" . round($grade->third_grading) . "</td><td>" . round($grade->fourth_grading) . "</td><td>".round($grade->final_grade)."</td>";
            $data = $data . "<td>". $grade->remarks . "</td><td></tr>";    
                    }
            $data = $data . "</table>";
           }
           
           
            $core = \App\Grade::where('idno',Input::get('idno'))->where('schoolyear',Input::get('sy'))->where('subjecttype','5')->orderBy('sortto')->get();
            if(count($core)>0){
            $data = $data . "<table class=\"table table-stripped\"><tr><td><span class=\"subjecttitle\">Core Subject</span></td><td>1</td><td>2</td><td>3</td><td>4</td><td>Final</td><td>Remarks</td></tr>";
            foreach($core as $grade){
            $data = $data . "<tr><td width=\"50%\">".$grade->subjectname."</td><td>".round($grade->first_grading)."</td><td>" . round($grade->second_grading) . ""
                    . "</td><td>" . round($grade->third_grading) . "</td><td>" . round($grade->fourth_grading) . "</td><td>" . round($grade->final_grade) 
                    . "</td><td>". $grade->remarks . "</td><td></tr>";    
            }
            $data = $data . "</table>";
            }
            
            $specialize = \App\Grade::where('idno',Input::get('idno'))->where('schoolyear',Input::get('sy'))->where('subjecttype','6')->orderBy('sortto')->get();
            if(count($specialize)>0){
            $data = $data . "<table class=\"table table-stripped\"><tr><td><span class=\"subjecttitle\">Specialize Subject</span></td><td>1</td><td>2</td><td>3</td><td>4</td><td>Final</td><td>Remarks</td></tr>";
            foreach($specialize as $grade){
            $data = $data . "<tr><td width=\"50%\">".$grade->subjectname."</td><td>".round($grade->first_grading)."</td><td>" . round($grade->second_grading) . ""
                    . "</td><td>" . round($grade->third_grading) . "</td><td>" . round($grade->fourth_grading) . "</td><td>" . round($grade->finalgrade) 
                    . "</td><td>". $grade->remarks . "</td><td></tr>";    
            }
            $data = $data . "</table>";
            }
            
            
            $technicals = \App\Grade::where('idno',Input::get('idno'))->where('schoolyear',Input::get('sy'))->where('subjecttype','1')->orderBy('sortto')->get();
            if(count($technicals)>0){
            
            $data = $data . "<table class=\"table table-stripped\"><tr><td width=\"50%\"><span class=\"subjecttitle\">Technical Subject</span></td><td>1</td><td>2</td><td>3</td><td>4</td><td>Final</td><td>Remarks</td></tr>";
            foreach($technicals as $grade){
            $data = $data . "<tr><td>".$grade->subjectname." (".$grade->weighted.")</td><td>".round($grade->first_grading)."</td><td>" . round($grade->second_grading) . ""
                    . "</td><td>" . round($grade->third_grading) . "</td><td>" . round($grade->fourth_grading) . "</td><td>" . round($grade->finalgrade) 
                    . "</td><td>". $grade->remarks . "</td><td></tr>";    
            }
            $data = $data . "</table>";
            }
            
            $conduct = \App\Grade::where('idno',Input::get('idno'))->where('schoolyear',Input::get('sy'))->where('subjecttype','3')->orderBy('sortto')->get();
            if(count($conduct)>0){
           
            $data = $data . "<table class=\"table table-stripped\"><tr><td width=\"40%\"><span class=\"subjecttitle\">Conduct Criteria</span></td><td>Points</td><td>1</td><td>2</td><td>3</td><td>4</td><td>Final</td><td>Remarks</td></tr>";
            foreach($conduct as $grade){
            $data = $data . "<tr><td>".$grade->subjectname."</td><td>".$grade->points."</td><td>".round($grade->first_grading)."</td><td>" . round($grade->second_grading) . ""
                    . "</td><td>" . round($grade->third_grading) . "</td><td>" . round($grade->fourth_grading) . "</td><td>" . round($grade->finalgrade) 
                    . "</td><td>". $grade->remarks . "</td><td></tr>";    
            }
            $data = $data . "</table>";
            }
            $attendance = \App\Grade::where('idno',Input::get('idno'))->where('schoolyear',Input::get('sy'))->where('subjecttype','2')->orderBy('sortto')->get();
            if(count($attendance)>0){
            
            $data = $data . "<table class=\"table table-stripped\"><tr><td width=\"50%\"><span class=\"subjecttitle\">Attendance</span></td><td>1</td><td>2</td><td>3</td><td>4</td><td>Final</td><td>Remarks</td></tr>";
            foreach($attendance as $grade){
            $data = $data . "<tr><td>".$grade->subjectname." (".$grade->weighted.")</td><td>".round($grade->first_grading)."</td><td>" . round($grade->second_grading) . ""
                    . "</td><td>" . round($grade->third_grading) . "</td><td>" . round($grade->fourth_grading) . "</td><td>" . round($grade->finalgrade) 
                    . "</td><td>". $grade->remarks . "</td><td></tr>";    
            }
            $data = $data . "</table>";
            }
            if($status->level == "Grade 11" ||$status->level == "Grade 12" ){
                $data = $data . "<a class='btn btn-danger' href='/card/".Input::get('idno')."/".Input::get('sy')."/1'>Print 1st Sem</a><span width='50px'>&nbsp;</span>";
                $data = $data . "<a class='btn btn-danger' href='/card/".Input::get('idno')."/".Input::get('sy')."/2'>Print 2nd Sem</a>";
            }else{
                $data = $data . "<a class='btn btn-danger' href='/card/".Input::get('idno')."/".Input::get('sy')."'>Print</a>";
            }
            
            return $data;
        }
        
            function getaccountcode(){
                $accountname = Input::get('accountname');
                $accountcode = \App\ChartOfAccount::where('accountname',$accountname)->first();
                return $accountcode->acctcode;
            }
            
            function getsubsidiary(){
                
                $data="";
                $acctcode = Input::get('acctcode');
                $subsidiaries = \App\CtrOtherPayment::where('acctcode',$acctcode)->get();
                if(count($subsidiaries)==0){
                    $data = "<option>None</option>";
                }else{
                    foreach($subsidiaries as $subsidiary){
                    $data = $data . "<option value=\"".$subsidiary->particular."\">".$subsidiary->particular."</option>";
                    }
                }
                return $data;
            }
            
            function postpartialentry(){
                if(Request::ajax()){
                    $fiscalyear = \App\CtrFiscalyear::first()->fiscalyear;
                    $refno = Input::get("refno");
                    $acctcode = Input::get("acctcode");
                    $accountname = Input::get("accountname");
                    $subsidiary = Input::get("subsidiary");
                    $department = Input::get("department");
                    $entrytype = Input::get("entrytype");
                    $entry_type=Input::get("entry_type");
                    $amount = Input::get("amount");
                    $referenceid =Input::get("referenceid");
                    //$trandate = \Carbon\Carbon::now();
                    
                    $newpartial = new \App\Accounting;
                    $newpartial->refno = $refno;
                    $newpartial->referenceid=$referenceid;
                    $newpartial->accountcode = $acctcode;
                    $newpartial->accountname = $accountname;
                    $newpartial->subsidiary = $subsidiary;
                    $newpartial->sub_department = $department;
                    $newpartial->acct_department = $this->getAcctDepartment($department);
                    if($entrytype=="cr"){
                        $newpartial->credit = $amount;
                        $newpartial->cr_db_indic="1";
                    }else{
                        $newpartial->debit = $amount;
                        $newpartial->cr_db_indic="0";
                    }
                    $newpartial->transactiondate = \Carbon\Carbon::now();
                    $newpartial->fiscalyear = $fiscalyear;
                    $newpartial->posted_by =Input::get('idno');
                    $newpartial->type = $entry_type;
                    $newpartial->save();  
                    return $this->getpartialentry($refno);
                }
            }
            function removeacctgpost(){
                $id = Input::get('id');
                $refno = Input::get('refno');
                $remove = \App\Accounting::find($id);
                $remove->forceDelete();
                return $this->getpartialentry($refno);
            }
            function getAcctDepartment($subDepartment){
                $department="";
                $acctdepartment = \App\CtrAcctDep::where('sub_department',$subDepartment)->first();
                if(count($acctdepartment)>0){
                  $department = $acctdepartment->main_department;  
                }
                return $department;
            }
            
            function getpartialentry($refno){
                    
                    $data="";
                    $totaldebit=0.00;
                    $totalcredit=0.00;
                    $displays = \App\Accounting::where('refno',$refno)->get();
                    if(count($displays)>0){
                    foreach($displays as $display){
                        $data = $data . "<tr><td>" . $display->accountcode . "</td>"
                                . "<td>" . $display->accountname . "</td>"
                                . "<td>" . $display->subsidiary . "</td>"
                                . "<td>" . $display->sub_department . "</td>"
                                . "<td align=\"right\">" . $display->debit . "</td>";
                        if($display->type=="3" || $display->type=="4"){
                         $data= $data . "<td align=\"right\">" . $display->credit. "</td>";
                        }
                        $data = $data. "<td> <button class=\"btn btn-default form-control\" onclick=\"removeacctgpost(" . $display->id . ")\">Remove</button></td> </tr>";
                    $totalcredit = $totalcredit + $display->credit;
                    $totaldebit = $totaldebit + $display->debit;    
                    }
                    if($totalcredit == $totaldebit){
                     $data = $data. "<td colspan=\"4\">Total<input type=\"hidden\" id=\"balance\" value=\"yes\"><input type=\"hidden\" id=\"crdrdiff\" value=\"" . ($totaldebit-$totalcredit) . "\"></td><td align=\"right\" style=\"font-weight:bold\">".number_format($totaldebit,2)."</td><td align=\"right\" style=\"font-weight:bold\">". number_format($totalcredit,2)."<input type=\"hidden\" value=\"$totalcredit\" name=\"totalcredit\" id=\"totalcredit\"></td><td><button id=\"removeall\"class=\"btn btn-danger form-control removeall\" onclick=\"removeall()\">Remove All</button></td></tr>";
                    }else{
                     if($display->type == '2'){
                     $data = $data. "<td colspan=\"4\">Total<input type=\"hidden\" id=\"balance\" value=\"no\"><input type=\"hidden\" id=\"crdrdiff\" value=\"" . ($totaldebit-$totalcredit) . "\"></td><td align=\"right\" style=\"font-weight:bold;color:red\">".number_format($totaldebit,2)."</td><td><button id=\"removeall\"class=\"btn btn-danger form-control removeall\" onclick=\"removeall()\">Remove All</button></td></tr>";    
                     }
                     else{
                     $data = $data. "<td colspan=\"4\">Total<input type=\"hidden\" id=\"balance\" value=\"no\"><input type=\"hidden\" id=\"crdrdiff\" value=\"" . ($totaldebit-$totalcredit) . "\"></td><td align=\"right\" style=\"font-weight:bold;color:red\">".number_format($totaldebit,2)."</td><td align=\"right\" style=\"font-weight:bold;color:red\">". number_format($totalcredit,2)."</td><td><button id=\"removeall\"class=\"btn btn-danger form-control removeall\" onclick=\"removeall()\">Remove All</button></td></tr>";    
                    }}}
                    return $data;    
            }
            
            function postacctgremarks(){
                if(Request::ajax()){
                    $particular = Input::get('particular');
                    $refno = Input::get('refno');
                    $idno = Input::get('idno');
                    $referenceid = Input::get("referenceid");
                    $amount = Input::get('totalcredit');
                    $entry_type=Input::get('entry_type');
                    \App\Accounting::where('refno',$refno)->update(['isfinal' => '1','transactiondate'=>\Carbon\Carbon::now()]);
                    $updates = \App\Accounting::where('refno',$refno)->get();
                    foreach ($updates as $update){
                        if($update->cr_db_indic == '1'){
                            $add = new \App\Credit;
                            $add->amount = $update->credit;
                        }else{
                            $add = new \App\Dedit;
                            $add->amount=$update->debit;
                        }
                         $add->transactiondate = \Carbon\Carbon::now();
                         $add->refno = $update->refno;
                         $add->receiptno = $update->referenceid;
                         $add->accountingcode = $update->accountcode;
                         $add->acctcode = $update->accountname;
                         $add->description = $update->subsidiary;
                         $add->entry_type = $entry_type;
                         $add->sub_department = $update->sub_department;
                         $add->fiscalyear = $update->fiscalyear;
                         $add->postedby = $idno;
                         $add->save();
                         
                    }
                    //$isfinal = DB::Select("update accountings set isfinal = '1' where refno = '$refno'");
                    $newparticular = new \App\AccountingRemark;
                    $newparticular->trandate = \Carbon\Carbon::now();
                    $newparticular->refno = $refno;
                    $newparticular->referenceid = $referenceid;
                    $newparticular->remarks = $particular;
                    $newparticular->amount = $amount;
                    $newparticular->posted_by = $idno;
                    $newparticular->save();
                    $incrementreceipt = \App\User::where('idno',$idno)->first();
                    $incrementreceipt->receiptno = $incrementreceipt->receiptno + 1;
                    $incrementreceipt->update();
                    return "done";
                }
            }
            function removeacctgall(){
                if(Request::ajax()){
                    $refno = Input::get('refno');
                    $removeall = \App\Accounting::where('refno',$refno)->get();
                    foreach($removeall as $ra){
                        $ra->forceDelete();
                    }
                    return "true";
                }
            }
            
            function getjournallist(){
               if(Request::ajax()){
               $voucherno = Input::get("search");
               $voucher = \App\AccountingRemark::where("referenceid",$voucherno)->first();
                   if(count($voucher)>0){
                       return view('accounting.searchjournallist',compact('voucher'));
                   } else {
                       return "<h1>Record Not Found!!! Please Enter Voucher Number Again!!";
                   }
               } 
            }
            
            function processdisbursement(){
                if(Request::ajax()){
                    $refno = Input::get("refno");
                    $voucherno = Input::get("voucherno");
                    $payee = Input::get("payee");
                    $checkno = Input::get("checkno");
                    $remarks=Input::get("remarks");
                    $bankaccount=Input::get('bankaccount');
                    $idno=Input::get('idno');
                    $entry_type=Input::get('entry_type');
                    $creditamount = Input::get('creditamount');
                    $accountcode = Input::get('accountcode');
                    
                    $addaccounting = new \App\Accounting;
                    $addaccounting->transactiondate = \Carbon\Carbon::now();
                    $addaccounting->refno = $refno;
                    $addaccounting->referenceid = $voucherno;
                    $addaccounting->accountcode = $accountcode;
                    $addaccounting->accountname = $this->getaccountname($accountcode);
                    $addaccounting->credit=$creditamount;
                    $addaccounting->fiscalyear = \App\CtrFiscalyear::first()->fiscalyear;
                    $addaccounting->posted_by=$idno;
                    $addaccounting->cr_db_indic = "1";
                    $addaccounting->isfinal = "1";
                    $addaccounting->type = "4";
                    $addaccounting->save();
                    
                    \App\Accounting::where('refno',$refno)->update(['isfinal' => '1','transactiondate'=>\Carbon\Carbon::now()]);
                    $updates = \App\Accounting::where('refno',$refno)->get();
                    foreach ($updates as $update){
                        if($update->cr_db_indic == '1'){
                            $add = new \App\Credit;
                            $add->amount = $update->credit;
                        }else{
                            $add = new \App\Dedit;
                            $add->amount=$update->debit;
                        }
                         $add->transactiondate = \Carbon\Carbon::now();
                         $add->refno = $update->refno;
                         $add->receiptno = $update->referenceid;
                         $add->accountingcode = $update->accountcode;
                         $add->acctcode = $update->accountname;
                         $add->description = $update->subsidiary;
                         $add->entry_type = $entry_type;
                         $add->acct_department = $update->acct_department;
                         $add->sub_department = $update->sub_department;
                         $add->fiscalyear = $update->fiscalyear;
                         $add->postedby = $idno;
                         $add->save();
                         
                    }
                    
                    $newdisbursement = new \App\Disbursement;
                    $newdisbursement->transactiondate = \Carbon\Carbon::now();
                    $newdisbursement->refno = $refno;
                    $newdisbursement->checkno = $checkno;
                    $newdisbursement->voucherno = $voucherno;
                    $newdisbursement->bank = $this->getaccountname($bankaccount);
                    $newdisbursement->amount = $creditamount;
                    $newdisbursement->remarks = $remarks;
                    $newdisbursement->payee=$payee;
                    $newdisbursement->postedby=$idno;
                    $newdisbursement->save();
                    
                    $incrementreceipt = \App\User::where('idno',$idno)->first();
                    $incrementreceipt->disbursementno = $incrementreceipt->disbursementno + 1;
                    $incrementreceipt->update();
                  return "true";  
                }
            }
            static function getaccountname($acctcode){
                return \App\ChartOfAccount::where('acctcode',$acctcode)->first()->accountname;
            }
 }
