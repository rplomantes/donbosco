<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssessmentController extends Controller
{
    
     
    public function __construct()
	{
		$this->middleware('auth');
	}
        
    
    //
    public function show(){ 
        return view('registrar.assessment');
    }
    
    public function evaluate($id){
        $balance = "";
        $reservation = 0;
        $currentschoolyear = "";
        $mydiscount="";
        $ledgers="";
        $deposit = 0;
        
        
        $student = \App\User::where('idno',$id)->first();
        $status = \App\Status::where('idno',$id)->first();
       
        if(count($status) > 0){
            
                if($status->department=="TVET"){
                    $currentschoolyear = \App\ctrSchoolYear::where('department',$status->department)->where('period',$status->period)->first();
                }else{
                    $currentschoolyear = \App\ctrSchoolYear::where('department', $status->department)->first();
                }
                $matchfields=["idno"=>$id, "schoolyear" =>$currentschoolyear->schoolyear, "period" => $currentschoolyear->period ];
                $mydiscount=  \App\Discount::where($matchfields)->first();
                $ledgers =  DB::Select("select sum(amount) as amount, sum(plandiscount) as plandiscount,  sum(otherdiscount) as otherdiscount,receipt_details  from ledgers
                                 where idno = '$id' and schoolyear = '".$currentschoolyear->schoolyear."'  and period = '". $currentschoolyear->period."' Group by receipt_details ");
            
        }
        $programs = DB::Select("select distinct department from ctr_levels");
        //$k_levels = \App\CtrLevel::where('department','Kindergarten')->get();
        //$elem_levels = \App\CtrLevel::where('department','Elementary')->get();
        //$jhs_levels = \App\CtrLevel::where('department','Junior High School')->get();
        //$shs_levels = \App\CtrLevel::where('department','Senior High School')->get();
        //$k11_tracks = \App\CtrTrack::where('level', 'Grade 11')->get();
        //$k12_tracks = \App\CtrTrack::where('level', 'Grade 12')->get();
        $courses = DB::Select("select distinct coursecode from ctr_tvet_subjects");
        $balances = DB::Select("select sum(amount) as amount, sum(plandiscount) as plandiscount, sum(otherdiscount) as otherdiscount, sum(debitmemo) as debitmemo, sum(payment) as payment from ledgers where idno = ?",array($id));
        if(count($balances) > 0 ){
            foreach($balances as $ledger){    
            $balance = $balance + $ledger->amount - $ledger->payment-$ledger->plandiscount-$ledger->debitmemo-$ledger->otherdiscount;
            }
        }
        $fields=['idno'=>$id, 'status'=>'0']; 
        $reservations = \App\AdvancePayment::where($fields)->get();
        if(count($reservations)> 0 ){
            foreach ($reservations as $res){
            $reservation = $reservation + $res->amount;
            }
        }
        
        $matchfields=['idno'=>$id, 'status'=>'1']; 
        $deposits = \App\StudentDeposit::where($matchfields)->get();
        if(count($deposits)> 0 ){
            foreach ($deposits as $depo){
            $deposit = $deposit + $depo->amount;
            }
        }
        
        
        return view('registrar.oldstudent', compact('reservation','student','status','balance','programs','k_levels','elem_levels','shs_levels','jhs_levels','k11_tracks','k12_tracks','courses','currentschoolyear','mydiscount','ledgers','deposit'));
    }
    
function assess(Request $request){
    $contribution="0";
    $batch="0";
    if($request->department=="TVET"){
    $schoolperiod = \App\ctrSchoolYear::where('period', $request->batch)->first();
    }else{
    $schoolperiod = \App\ctrSchoolYear::where("department",$request->department)->first();
    }
    
    if($request->department == "TVET"){
        $tf = $request->tuitionfee;
        $misc = $request->misc;
        $gradfee = $request->gradfee;
        $discount = $request->discount;
        $paidby_tuitionfee = $request->paidby_tuitionfee;
        $paidby_misc=$request->paidby_misc;
        $paidby_gradfee=$request->paidby_gradfee;
        $contribution = $request->contribution;
        $batch=$request->batch;
        
        if($request->action != "reassessed"){
            $findtvet=  \App\TvetSubsidy::where('idno',$request->idno)->first();
            if(count($findtvet)==0){
                $sponsor=0;
                $subsidy =0;

                if($paidby_tuitionfee=="sponsor"){
                    $sponsor=$sponsor + ($tf - ($discount/100) * $tf);
                }

                if($paidby_misc=="sponsor"){
                    $sponsor=$sponsor + $misc; 
                }

                if($paidby_gradfee=="sponsor"){
                    $sponsor=$sponsor + $gradfee; 
                }

                $addtvet = new \App\TvetSubsidy;
                $addtvet->idno=$request->idno;
                $addtvet->sponsor=$sponsor;
                $addtvet->subsidy=$tf*$discount/100;
                $addtvet->discount=0;
                $addtvet->batch=$batch;
                $addtvet->save();      
            }
        }
    }
    
    if(isset($request->strand)){
        $strand = $request->strand;
    } else{
        $strand="";
    }   
    
    if(isset($request->course)){
        $course = $request->course;
    }else{
        $course="";
    }
    
     if(isset($request->level)){
        $level = $request->level;
    }else{
        $level="";
    }
    
    
    $action = $request->action;
    
    switch($action){
    
    case "add":
        
              if($this->addLedger($request->id,$level,$request->plan,$request->discount,$request->department,$strand,$course,$contribution,$batch)){  
                
                  if(isset($request->books)){
        $books = $request->books;
                   foreach($books as $book){
                      
                       $paidbook = \App\CtrBook::where('id',$book)->first();
                       $newbook = new \App\Ledger;
                       $newbook->idno = $request->idno;
                       $newbook->transactiondate = Carbon::now();
                       $newbook->department = $request->department;
                       
                       $newbook->level = $level;
                       $newbook->course = $course;
                        //$newledger->track = $track;
                       $newbook->strand= $strand;
                       $newbook->categoryswitch = $paidbook->categoryswitch;
                       $newbook->accountingcode = $paidbook->acctcode;
                       $newbook->acctcode = $paidbook->acctname;
                       $newbook->description = $paidbook->subsidiary;
                       $newbook->receipt_details = $paidbook->receipt_details;
                       $newbook->amount = $paidbook->amount;
                       $newbook->acct_department = $paidbook->acct_department;
                       $newbook->sub_department = $paidbook->sub_department;
                       $newbook->schoolyear = $schoolperiod->schoolyear;
                       $newbook->duetype = 0;
                       $newbook->period = $schoolperiod->period;
                       $newbook->duedate = $paidbook->duedate;
                       $newbook->postedby = \Auth::user()->id;
                       $newbook->save();
                   } 
                
    }
    
                  
                $status = new \App\Status;
                $status->idno=$request->id;
                $status->date_registered=Carbon::now();
                if($request->department == "TVET" && $contribution == "0"){
                $status->status = "2";    
                }else{
                $status->status = "1";
                }
                $status->department = $request->department;
                    if($request->level == 'Grade 9' || $request->level == 'Grade 10' || $request->level == 'Grade 11' || $request->level == 'Grade 12'){
                        $status->strand = $request->strand;
                    }
                 
                    if($request->department == "TVET"){
                        $status->course = $course;
                    }
                        else{  
                        $status->level = $request->level;
                    }
                    
                $status->plan=$request->plan;
                $status->schoolyear= $schoolperiod->schoolyear;
                $status->period=$schoolperiod->period;
                $status->save();
              }   
                 
        //return redirect('registrar/evaluate/'. $request->id);
              return $this->evaluate($request->id);
        break;
        
    case "addnew":
        $newstudent = new \App\User;
        $newstudent->idno = $request->idno;
        $newstudent->lastname = $request->lastname;
        $newstudent->firstname = $request->firstname;
        $newstudent->middlename=$request->middlename;
        $newstudent->extensionname=$request->extensionname;
        $newstudent->gender=$request->gender;
        $newstudent->password = bcrypt($request->idno);
        $newstudent->save();
        
        if($this->addLedger($request->idno,$level,$request->plan,$request->discount,$request->department, $strand,$course,$contribution,$batch)){  
            
            if(isset($request->books)){
                       $books = $request->books;
                       foreach($books as $book){
                       $paidbook = \App\CtrBook::where('id',$book)->first();
                       $newbook = new \App\Ledger;
                       $newbook->idno = $request->idno;
                       $newbook->transactiondate = Carbon::now();
                       $newbook->department = $request->department;
                       
                       $newbook->level = $level;
                       $newbook->course = $course;
                        //$newledger->track = $track;
                       $newbook->strand= $strand;
                       $newbook->categoryswitch = $paidbook->categoryswitch;
                       $newbook->accountingcode = $paidbook->acctcode;
                       $newbook->acctcode = $paidbook->acctname;
                       $newbook->description = $paidbook->subsidiary;
                       $newbook->receipt_details = $paidbook->receipt_details;
                       $newbook->amount = $paidbook->amount;
                       $newbook->acct_department = $paidbook->acct_department;
                       $newbook->sub_department = $paidbook->sub_department;
                       $newbook->schoolyear = $schoolperiod->schoolyear;
                       $newbook->duetype = 0;
                       $newbook->period = $schoolperiod->period;
                       $newbook->duedate = $paidbook->duedate;
                       $newbook->postedby = \Auth::user()->id;
                       $newbook->save();
                   } 
                
    }
    
            
            $status = new \App\Status;
                $status->idno=$request->idno;
                $status->date_registered=Carbon::now();
                if($request->department == "TVET" && $contribution == "0"){
                $status->status = "2";    
                }else{
                $status->status = "1";
                }
                $status->department = $request->department;
                    if($request->level == 'Grade 9' || $request->level == 'Grade 10' || $request->level == 'Grade 11' || $request->level == 'Grade 12'){
                        $status->strand = $request->strand;
                    }
                    
                 if($request->department == "TVET"){
                 $status->course = $course;
                 }
                 else{  
                  $status->level = $request->level;
                 }
                    
                $status->plan=$request->plan;
                $status->schoolyear= $schoolperiod->schoolyear;
                $status->isnew = "1";
                $status->period=$schoolperiod->period;
                $status->save();
              }
        //return redirect('registrar/evaluate/'. $request->idno);
              return $this->evaluate($request->idno);
        break;
    
    case "reassessed";
       
        $deletesubsidy= \App\TvetSubsidy::where('idno',$request->id)->first();
        if(count($deletesubsidy)>0){
            $deletesubsidy->delete();
        }
        $matchfields=["idno"=>$request->id, "schoolyear"=>$schoolperiod->schoolyear, "period" => $schoolperiod->period];
        $deletestudents = \App\Ledger::where($matchfields)->get();
        foreach($deletestudents as $deletestudent){
            $deletestudent->delete();
        }
        
       
        $deletegrades = \App\Grade::where($matchfields)->get();
        foreach($deletegrades as $deletegrate){
            $deletegrate->delete();
        }
        
        $deletediscounts = \App\Discount::where($matchfields)->get();
        foreach ($deletediscounts as $deletediscount){
            $deletediscount->delete();
        }
        $matchfields=["idno"=>$request->id, "schoolyear"=>$schoolperiod->schoolyear, "period" => $schoolperiod->period, "accountingcode" => "440400"];
        $deletebooks = \App\Ledger::where($matchfields)->get();
        foreach($deletebooks as $deletebook){
            $deletebook->delete();
        }
        $changestatus =  \App\Status::where('idno',$request->id)->first();
        $changestatus->status = "0";
        $changestatus->strand = "";
        $changestatus->course="";
        $changestatus->track="";
        $changestatus->level="";
        //$changestatus->period="";
        $changestatus->update();
        
        $ress=  \App\AdvancePayment::where('idno',$request->id)->where('status','0')->get();
        if(count($ress)>0){
            foreach($ress as $res){
                \App\AdvancePayment::where('id',$res->id)->update(['status'=>'2']);
            }
        }
        
        
        $deposits = \App\StudentDeposit::where('idno',$request->id)->where('status','1')->get();
        if(count($deposits)>0){
            foreach($deposits as $deposit){
                \App\StudentDeposit::where('id',$deposit->id)->update(['status'=>'0']);
            }
        }
        
        //return redirect('registrar/evaluate/'. $request->id);
        return $this->evaluate($request->id);
        
        
        //return $request->department;
        break;
        
    case "update";
        $this->archiveStatus($request->id);
            if($request->department == "Kindergarten" || $request->department =="Elementary" || $request->department == "Junior High School" || $request->department == "Senior High School" ||$request->department == "TVET" ){
              if($this->addLedger($request->id,$request->level,$request->plan,$request->discount,$request->department,$strand,$course,$contribution,$batch)){  
                if(isset($request->books)){
                       $books = $request->books;
                       foreach($books as $book){
                       $paidbook = \App\CtrBook::where('id',$book)->first();
                       $newbook = new \App\Ledger;
                       $newbook->idno = $request->idno;
                       $newbook->transactiondate = Carbon::now();
                       $newbook->department = $request->department;
                       
                       $newbook->level = $level;
                       $newbook->course = $course;
                        //$newledger->track = $track;
                       $newbook->strand= $strand;
                       $newbook->categoryswitch = $paidbook->categoryswitch;
                       $newbook->accountingcode = $paidbook->acctcode;
                       $newbook->acctcode = $paidbook->acctname;
                       $newbook->description = $paidbook->subsidiary;
                       $newbook->receipt_details = $paidbook->receipt_details;
                       $newbook->amount = $paidbook->amount;
                       $newbook->acct_department = $paidbook->acct_department;
                       $newbook->sub_department = $paidbook->sub_department;
                       $newbook->schoolyear = $schoolperiod->schoolyear;
                       $newbook->duetype = 0;
                       $newbook->period = $schoolperiod->period;
                       $newbook->duedate = $paidbook->duedate;
                       $newbook->postedby = \Auth::user()->id;
                       $newbook->save();
                   } 
                
    }
                  
                $status = \App\Status::where('idno',$request->id)->first();
                $status->date_registered=Carbon::now();
                if($request->department == "TVET" && $contribution == "0"){
                $status->status = "2";    
                }else{
                $status->status = "1";
                }
                $status->department = $request->department;
               
                 if($request->level == 'Grade 9' || $request->level == 'Grade 10' || $request->level == 'Grade 11' || $request->level == 'Grade 12'){
                $status->strand = $request->strand;
                }
                
                  if($request->department == "TVET"){
                 $status->course = $course;
                 }
                 else{  
                  $status->level = $request->level;
                 }
                $status->plan=$request->plan;
                $status->schoolyear= $schoolperiod->schoolyear;
                $status->period=$schoolperiod->period;
                $status->update();
              }   
            }
            //return redirect('registrar/evaluate/'. $request->id);
            return $this->evaluate($request->id);
        break;    
    
    }
    
}
      
    

    function addLedger($id, $level, $plan, $discount,$department,$strand, $course,$contribution,$batch){
        if($department=="TVET"){
            $schoolperiod = \App\ctrSchoolYear::where('department','TVET')->where('period',$batch)->first();    
        }
        else{
            $schoolperiod = \App\ctrSchoolYear::where("department",$department)->first();
        }
        
        $discounts = \App\CtrDiscount::where('discountcode',$discount)->first();
        
        //Set Match Field
/*        if($department == "TVET"){
            $matchfields = ["department"=>"TVET", 'plan'=>$plan,"course"=> $course,"period"=>$batch];  
        }else{ 
            if($level == "Grade 9" || $level == "Grade 10" || $level == "Grade 11" || $level == "Grade 12"){
                $matchfields=['level'=>$level, 'plan' =>$plan, 'strand'=>$strand];
            }else {
                $matchfields=['level'=>$level, 'plan' =>$plan];
            }    
        }
 */
        if($department != 'TVET'){ 
            if($level == "Grade 9" || $level == "Grade 10" || $level == "Grade 11" || $level == "Grade 12"){
                $matchfields=['level'=>$level, 'plan' =>$plan, 'strand'=>$strand];
            }else {
                $matchfields=['level'=>$level, 'plan' =>$plan];
            }    
        }
        DB::table('ledgers')
            ->where('idno',$id)
            ->where('categoryswitch','<',10)
            ->update([
            'categoryswitch' => DB::raw('categoryswitch + 10')
        ]);

        if($department=="TVET"){
            $newledger = new \App\Ledger;
            $newledger->idno = $id;
            $newledger->transactiondate = Carbon::now();
            $newledger->department = $department;
           // $newledger->level = $ledger->level;
            $newledger->course = $course;
           // $newledger->track = $ledger->track;
           // $newledger->strand= $ledger->strand;
            $newledger->categoryswitch = "7";
            $newledger->accountingcode = "440000";
            $newledger->acctcode = "Trainees Contribution";
            $newledger->description = "Trainees Contribution";
            $newledger->receipt_details = "Trainees Contribution";
            $newledger->amount = $contribution;
            $newledger->acct_department = "TVET";
            $newledger->sub_department = "TVET";
            //$newledger->plandiscount = $ledger->discount;
            $newledger->schoolyear = $schoolperiod->schoolyear;
            $newledger->duetype = "1";
            $newledger->period = $batch;
            $newledger->duedate = Carbon::now();
            $newledger->postedby = \Auth::user()->id;
            $newledger->save();
        }else{
            $ledgers = \App\CtrPaymentSchedule::where($matchfields)->get();
            
            foreach($ledgers as $ledger){
                $newledger = new \App\Ledger;
                $newledger->idno = $id;
                $newledger->transactiondate = Carbon::now();
                $newledger->department = $ledger->department;
                $newledger->level = $ledger->level;
                $newledger->course = $ledger->course;
                $newledger->track = $ledger->track;
                $newledger->strand= $ledger->strand;
                $newledger->categoryswitch = $ledger->categoryswitch;
                $newledger->accountingcode = $ledger->accountingcode;
                $newledger->acctcode = $ledger->acctcode;
                $newledger->description = $ledger->description;
                $newledger->acct_department = $ledger->acct_department;
                $newledger->sub_department = $ledger->sub_department;
                $newledger->receipt_details = $ledger->receipt_details;
                $newledger->amount = $ledger->amount;
                    if($ledger->categoryswitch == env('TUITION_FEE')){
                        if(isset($discounts->discountcode)){    
        //if(count($discounts)> 0){
                            $totaldiscount = (($ledger->amount-$ledger->discount) * ($discounts->tuitionfee/100));    
                            $newledger->otherdiscount = $totaldiscount;
                            $newledger->discountcode = $discounts->discountcode;
                           }

                    }
                    if($ledger->categoryswitch == env('DEPARTMENT_FEE')){
                        if(isset($discounts->discountcode)){    
        //if(count($discounts)> 0){
                            $totaldiscount = (($ledger->amount-$ledger->discount) * ($discounts->departmentfee/100));    
                            $newledger->otherdiscount = $totaldiscount;
                            $newledger->discountcode = $discounts->discountcode;
                           }

                    }
                $newledger->plandiscount = $ledger->discount;
                $newledger->schoolyear = $schoolperiod->schoolyear;
                $newledger->duetype = $ledger->duetype;
                $newledger->period = $schoolperiod->period;
                $newledger->duedate = $ledger->duedate;
                $newledger->postedby = \Auth::user()->id;
                $newledger->save();

                if(isset($discounts->discountcode)){
                    if($discounts->discountcode !="None" && $ledger->categoryswitch == env('TUITION_FEE')){
                        $studentdiscount = new \App\Discount;
                        $studentdiscount->transactiondate = Carbon::now();
                        $studentdiscount->idno = $id;
                        $studentdiscount->refid = $newledger->id;
                        $studentdiscount->plan=$plan;
                        $studentdiscount->discountcode = $discounts->discountcode;
                        $studentdiscount->description =$discounts->description;
                        $studentdiscount->tuitionfee = $discounts->tuitionfee;
                        $studentdiscount->registrationfee=$discounts->registrationfee;
                        $studentdiscount->miscellaneousfee=$discounts->miscellaneousfee;
                        $studentdiscount->elearningfee=$discounts->elearningfee;
                        $studentdiscount->departmentfee=$discounts->departmentfee;
                        $studentdiscount->bookfee=$discounts->bookfee;
                        $studentdiscount->amount=$totaldiscount;
                        $studentdiscount->duedate = $newledger->duedate;
                        $studentdiscount->schoolyear=$schoolperiod->schoolyear;
                        $studentdiscount->period=$schoolperiod->period;
                        $studentdiscount->save(); 
                    }           

                }
            }
        }  
                if($department == "Kindergarten" || $department == "Elementary" || $department == "Junior High School"){ 
                $newsubjects = \App\CtrSubjects::where('level',$level)->get();
                foreach($newsubjects as $newsubject){
                    $newgrade = new \App\Grade;
                    $newgrade->idno = $id;
                    $newgrade->level = $level;
                    $newgrade->subjectcode=$newsubject->subjectcode;
                    $newgrade->subjectname=$newsubject->subjectname;
                    $newgrade->schoolyear=$schoolperiod->schoolyear;
                    $newgrade->period=$schoolperiod->period;
                    $newgrade->subjecttype=$newsubject->subjecttype;
                    $newgrade->points=$newsubject->points;
                    $newgrade->weighted=$newsubject->weighted;
                    $newgrade->sortto=$newsubject->sortto;
                    $newgrade->save();
                }
                    
                } elseif($department == "Senior High School"){
                    $matsubject = ["level"=>$level, "strand"=>$strand];
                    $newsubjects = \App\CtrSubjects::where($matsubject)->get();
                    foreach($newsubjects as $newsubject){
                    $newgrade = new \App\Grade;
                    $newgrade->idno = $id;
                    $newgrade->level = $level;
                    $newgrade->strand=$newsubject->strand;
                    $newgrade->subjectcode=$newsubject->subjectcode;
                    $newgrade->subjectname=$newsubject->subjectname;
                    $newgrade->schoolyear=$schoolperiod->schoolyear;
                    $newgrade->period=$schoolperiod->period;
                    $newgrade->subjecttype=$newsubject->subjecttype;
                    $newgrade->points=$newsubject->points;
                    $newgrade->weighted=$newsubject->weighted;
                    $newgrade->sortto=$newsubject->sortto;
                    $newgrade->save();
                }
                
                    } elseif($department == "TVET"){
                      
                    $newsubjects = \App\CtrSubjects::where('course',$course)->get();
                    foreach($newsubjects as $newsubject){
                    $newgrade = new \App\Grade;
                    $newgrade->idno = $id;
                    $newgrade->course = $course;
                    $newgrade->strand=$newsubject->strand;
                    $newgrade->subjectcode=$newsubject->subjectcode;
                    $newgrade->subjectname=$newsubject->subjectname;
                    $newgrade->schoolyear=$schoolperiod->schoolyear;
                    $newgrade->period=$schoolperiod->period;
                    $newgrade->subjecttype=$newsubject->subjecttype;
                    $newgrade->points=$newsubject->points;
                    $newgrade->weighted=$newsubject->weighted;
                    $newgrade->sortto=$newsubject->sortto;
                    $newgrade->save();
                }
                }   
                $ress = \App\AdvancePayment::where('idno',$id)->where('status','2')->get();
                if(count($ress)>0){
                    foreach($ress as $res){
                       \App\AdvancePayment::where('id',$res->id)->update(['status'=>'0']);
                    }
                }
                
                $deposits = \App\StudentDeposit::where('idno',$id)->where('status','0')->get();
                if(count($deposits)>0){
                    foreach($deposits as $deposit){
                        \App\StudentDeposit::where('id',$deposit->id)->update(['status'=>'1']);
                    }
                }
                
                 return true;
    }

    function printregistration($idno){
        $status = \App\Status::where('idno',$idno)->first();
        $user = \App\User::where('idno',$idno)->first();
        if($status->department=="TVET"){
         $matchfiels=['idno'=>$idno, 'schoolyear'=>$status->schoolyear, 'period'=>$status->period];
     
        }else{
        $matchfiels=['idno'=>$idno, 'schoolyear'=>$status->schoolyear, 'period'=>$status->period,'categoryswitch'=>'1'];
    }
        $ledger = \App\Ledger::where($matchfiels)->first();
        $postedby = \App\User::where('id',$ledger->postedby)->first();
        $breakdownfees = DB::Select("select idno, sum(amount) as amount, sum(plandiscount) as plandiscount, sum(otherdiscount) as otherdiscount,categoryswitch, receipt_details,postedby from ledgers
                where idno = '$idno' and schoolyear = '". $status->schoolyear ."' and period = '".$status->period."'group by idno, categoryswitch, receipt_details, postedby");
        
        $dues = DB::Select("select idno, sum(amount) as amount, sum(plandiscount) as plandiscount, sum(otherdiscount) as otherdiscount, "
                . "duetype, duedate from ledgers where idno = '$idno' and schoolyear = '". $status->schoolyear ."' and period = '".$status->period."' "
                . " group by idno, duetype, duedate order by duedate");
        $matchfields=['idno'=>$idno, 'status'=>'0'];
        $reservation = \App\AdvancePayment::where($matchfields)->first();
        
        $matchfields=['idno'=>$idno, 'status'=>'1']; 
        $deposit = 0;
        $deposits = \App\StudentDeposit::where($matchfields)->get();
        if(count($deposits)> 0 ){
            foreach ($deposits as $depo){
            $deposit = $deposit + $depo->amount;
            }
        }
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper("Folio", "portrait");
        if($status->department=="TVET"){
        $pdf->loadView('print.registrationtvet',compact('ledger','postedby','dues','status','user','ledgers','breakdownfees','reservation'));    
        }else{
        $pdf->loadView('print.registration',compact('ledger','postedby','dues','status','user','ledgers','breakdownfees','reservation','deposit'));
        }
        return $pdf->stream();
       
    }
    
    public function edit($id){
        
        $student = \App\User::where('idno',$id)->first();
        return view('registrar.editname', compact('student'));
        
    }
    
    public function editname(Request $request){
        $updatename = \App\User::where('idno',$request->idno)->first();
        $updatename->lastname = $request->lastname;
        $updatename->firstname = $request->firstname;
        $updatename->middlename=$request->middlename;
        $updatename->extensionname=$request->extensionname;
        $updatename->gender=$request->gender;
        $updatename->update();
        
       // return redirect(url('/registrar/evaluate',$request->idno));
        return $this->evaluate($request->idno);
    }
   
    function updategrades(){
        
    
        $students = \App\Status::where('status','2')->where('level','!=','Grade 11')->where('department','!=','TVET')->get();
        foreach($students as $student ){
            $subjects = \App\CtrSubjects::where('level',$student->level)->get();
                foreach($subjects as $newsubject){
                    $newgrade = new \App\Grade;
                    $newgrade->idno = $student->idno;
                    $newgrade->course = $student->course;
                    $newgrade->strand=$student->strand;
                    $newgrade->department=$student->department;
                    $newgrade->level=$student->level;
                    $newgrade->subjectcode=$newsubject->subjectcode;
                    $newgrade->subjectname=$newsubject->subjectname;
                    $newgrade->schoolyear=$student->schoolyear;
                    $newgrade->period=$student->period;
                    $newgrade->subjecttype=$newsubject->subjecttype;
                    $newgrade->points=$newsubject->points;
                    $newgrade->weighted=$newsubject->weighted;
                    $newgrade->sortto=$newsubject->sortto;
                    $newgrade->save();
    }}
          
         /*
    $seniorhigh =\App\Status::where('status',2)->where('level','Grade 11')->get();
    foreach($seniorhigh as $student){
        $subjects = \App\CtrSubjects::where('level',$student->level)->where('strand',$student->strand)->get();
                foreach($subjects as $newsubject){
                    $newgrade = new \App\Grade;
                    $newgrade->idno = $student->idno;
                    $newgrade->course = $student->course;
                    $newgrade->strand=$student->strand;
                    $newgrade->department=$student->department;
                    $newgrade->level=$student->level;
                    $newgrade->subjectcode=$newsubject->subjectcode;
                    $newgrade->subjectname=$newsubject->subjectname;
                    $newgrade->schoolyear=$student->schoolyear;
                    $newgrade->period=$student->period;
                    $newgrade->subjecttype=$newsubject->subjecttype;
                    $newgrade->points=$newsubject->points;
                    $newgrade->weighted=$newsubject->weighted;
                    $newgrade->sortto=$newsubject->sortto;
                    $newgrade->save();
                }
        
    }
          * 
          */
}
function updateelective(){
    $updates = DB::Select("select * from adjustelective2");
    foreach($updates as $update){
        $elective = \App\Grade::where('idno',$update->idno)->where('subjectcode','ELE2')->first();
        $elective->subjectname = "Elective 2 (" .$update->subjectname .")";
        $elective->update();
    }
}


function updatemapeh(){
        
        $students = \App\Status::where('status','2')->where('level','!=','Grade 11')->where('department','!=','TVET')->get();
        foreach($students as $student ){
            $subjects = DB::Select("select * from ctr_subjects where level = '" . $student->level ."' and isdisplaycard = '0'");
                foreach($subjects as $newsubject){
                    $newgrade = new \App\Grade;
                    $newgrade->idno = $student->idno;
                    //$newgrade->course = $student->course;
                    //$newgrade->strand=$student->strand;
                    $newgrade->department=$student->department;
                    $newgrade->level=$student->level;
                    $newgrade->subjectcode=$newsubject->subjectcode;
                    $newgrade->subjectname=$newsubject->subjectname;
                    $newgrade->schoolyear=$student->schoolyear;
                    $newgrade->period=$student->period;
                    $newgrade->subjecttype=$newsubject->subjecttype;
                    //$newgrade->points=$newsubject->points;
                    //$newgrade->weighted=$newsubject->weighted;
                    $newgrade->isdisplaycard=$newsubject->isdisplaycard;
                    $newgrade->sortto=$newsubject->sortto;
                    $newgrade->save();
}}}


function archiveStatus($idno){
    $student = \App\Status::where('idno',$idno)->first();
    
    $check = \App\StatusHistory::where('idno',$idno)->where('schoolyear',$student->schoolyear)->first();
    
    if(empty($check)){
        $this->addStatusHistory($student);
    }
}

    function addStatusHistory($student){
        $archive = new \App\StatusHistory();
        $archive->idno = $student->idno;
        $archive->date_registered = $student->date_registered;
        $archive->date_enrolled = $student->date_enrolled;
        $archive->status = $student->status;
        $archive->dropdate = $student->dropdate;
        $archive->department = $student->department;
        $archive->level = $student->level;
        $archive->track = $student->track;
        $archive->strand = $student->strand;
        $archive->course = $student->course;
        $archive->section = $student->section;
        $archive->class_no = $student->class_no;
        $archive->plan = $student->plan;
        $archive->schoolyear = $student->schoolyear;
        $archive->isnew = $student->isnew;
        $archive->isesc = $student->isesc;
        $archive->save();
    }
}