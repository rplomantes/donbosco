<?php
namespace App\Http\Controllers\Registrar;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

class Studentinfokto12Controller extends Controller
{
    
public function studentinfokto12()
{   //$student = \App\User::where('idno',$idno)->first();
    //$studentInfo = \App\StudentInfo::where('idno',$idno)->first();
    $student = NULL;
    $studentInfo = NULL;
    $sibling = NULL;
    $status = NULL;
    
    return view('registrar.studentinfokto12',compact('student','studentInfo','sibling','status'));
    //return view('registrar.studentinfokto12');
    
   
}

public function studentinfokto12edit($idno){
    $sy = \App\CtrRefSchoolyear::first()->schoolyear;
    $status = \App\Status::where('idno',$idno)->where('schoolyear',$sy)->orderBy('id','DESC')->first();
    $student = \App\User::where('idno',$idno)->first();
    $studentcount = \App\User::where('idno',$idno)->count();
    $studentInfo = \App\StudentInfo::where('idno',$idno)->first();
    $sibling = DB::Select("Select * from siblings where idno='$idno' order by sort asc");
    if(empty($studentInfo)){
        $studentInfo =new \App\StudentInfo();
        $studentInfo->idno = $idno;
        $studentInfo->save();        
    }
    if($studentcount == 0){
        return redirect ('studentinfokto12');
    }
    //return $sy;
    return view('registrar.studentinfokto12',compact('student','studentInfo','sibling','status'));
    
}

public function printInfo($idno)
{   $student = \App\User::where('idno',$idno)->first();
    $studentcount = \App\User::where('idno',$idno)->count();
    $studentInfo = \App\StudentInfo::where('idno',$idno)->first();
    $sibling = DB::Select("Select * from siblings where idno='$idno' order by sort asc");
    
    $birthDate = strtotime($studentInfo->birthDate);
    $birthYear = date('Y',$birthDate);  
    $currYear = date('Y');
    
    $age = $currYear - $birthYear;
    
    $birthMonth = date('Y',$birthDate);  
    $currMonth = date('Y');
    
    if($currMonth < $birthMonth){
        $age = $age-1;
    }
    
    if($studentcount == 0){
        return redirect ('studentinfokto12');
    }
    
    $pdf = \App::make('dompdf.wrapper');
    $pdf->setPaper('legal','portrait');
    $pdf->loadView('print.studentInfo',compact('student','studentInfo','sibling','age'));
    return $pdf->stream();
    
    //return view('print.studentInfo',compact('student','studentInfo','sibling','age'));
    
}

public function dateFormat($date){
    $time = strtotime($date);
    $newdate = date('Y-m-d',$time);
    if($date == NULL){
        $newdate =$date;
    }
    return $newdate;
}

public function saveInfo(Request $request){
         $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
        ]);
    
    $student = new \App\User();
    $student->idno = $request->idno;
    $student->save();
    $this->saveUser($request->all(), $student->id);
  
    $studentInfo =new \App\StudentInfo();
    $studentInfo->idno = $request->idno;
    $studentInfo->save();
    $this->saveStudentInfo($request->all(), $studentInfo->id);
       
    $this->createSibling($request->all());
    
    return redirect('studentinfokto12/'.$request->idno);
    
    //return $request;
}

public function updateInfo(Request $request,$idno){
            $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',

            
        ]);
    
    $student = \App\User::where('idno',$idno)->first();
    $this->saveUser($request->all(), $student->id);
  
    $studentInfo =\App\StudentInfo::where('idno',$idno)->first();;
    $this->saveStudentInfo($request->all(), $studentInfo->id);
    
    $matchfields=["idno"=>$idno];   
    $siblingcount = \App\Sibling::where($matchfields)->count();
    if($siblingcount == 0){
        $this->createSibling($request->all());
    }
    else{
        $this->updateSibling($request->all(),$idno);
    }
    return redirect('studentinfokto12/'.$idno);
    
    //return $request;
}

public function deleteStudent($idno){
    \App\User::where('idno',$idno)->delete();
    
    \App\StudentInfo::where('idno',$idno)->delete();
    
    \App\Sibling::where('idno',$idno)->delete();
    
    return redirect('studentinfokto12');
}

public function saveUser(array $request,$studentid){
    
    $student = \App\User::find($studentid);
    $student->lastname = $request['lastname'];
    $student->firstname = $request['firstname'];
    $student->middlename = $request['middlename'];
    $student->extensionname = $request['extensionname'];
    $student->gender = $request['gender'];
    $student->password = bcrypt($student->idno);
    $student->email = $request['email'];
    $student->save();
    
    return null;
}
public function saveStudentInfo(array $request,$studentid){
    
    $esc = Input::has('esc') ? 1 : 0;
    
    $studentInfo = \App\StudentInfo::find($studentid);    
    $studentInfo->birthDate = $this->dateFormat($request['birthDate']);
    $studentInfo->birthPlace = $request['birthPlace'];
    $studentInfo->citizenship = $request['citizenship'];
    $studentInfo->religion = $request['religion'];
    if(isset($request['status'])){
    $studentInfo->status = $request['status'];
    }
    $studentInfo->acr = $request['acr'];
    $studentInfo->visaType = $request['visaType'];
    $studentInfo->address1 = $request['address1'];
    $studentInfo->address2 = $request['address2'];
    $studentInfo->address3 = $request['address3'];
    $studentInfo->address4 = $request['address4'];
    $studentInfo->address5 = $request['address5'];
    $studentInfo->zipcode = $request['zipcode'];
    $studentInfo->address6 = $request['address6'];
    $studentInfo->address7 = $request['address7'];
    $studentInfo->address8 = $request['address8'];
    $studentInfo->address9 = $request['address9'];
    $studentInfo->phone1 = $request['phone1'];
    $studentInfo->phone2 = $request['phone2'];
    $studentInfo->lastattended = $request['lastattended'];
    $studentInfo->lastlevel = $request['lastlevel'];
    $studentInfo->lastyear = $request['lastyear'];
    $studentInfo->countboys = $request['countboys'];
    $studentInfo->countgirls = $request['countgirls'];
    $studentInfo->lrn = $request['lrn'];
    $studentInfo->esc = $esc;
    $studentInfo->escNo = $request['escNo'];

    $studentInfo->fname = $request['fname'];
    $studentInfo->fbirthdate = $this->dateFormat($request['fbirthdate']);
    $studentInfo->falumnus = $request['falumnus'];
    //$studentInfo->fgraduated = $request['fgraduated'];
    $studentInfo->fyeargraduated = $request['fyeargraduated'];
    if(isset($request['fstatus'])){
    $studentInfo->fstatus = $request['fstatus'];
    }
    $studentInfo->fcourse = $request['fcourse'];
    $studentInfo->fmobile = $request['fmobile'];
    $studentInfo->flandline = $request['flandline'];
    $studentInfo->freligion = $request['freligion'];
    $studentInfo->fnationality = $request['fnationality'];
    $studentInfo->fselfemployed = $request['fselfemployed'];
    $studentInfo->fFulljob = $request['fFulljob'];
    $studentInfo->fPartjob = $request['fPartjob'];
    if(isset($request['fposition'])){
    $studentInfo->fposition = $request['fposition'];
    }
    $studentInfo->fincome = $request['fincome'];
    $studentInfo->fcompany = $request['fcompany'];
    $studentInfo->fComAdd = $request['fComAdd'];
    $studentInfo->fOfficePhone = $request['fOfficePhone'];
    $studentInfo->ffax = $request['ffax'];
    $studentInfo->femail = $request['femail'];
    
    $studentInfo->mname = $request['mname'];
    $studentInfo->mbirthdate = $this->dateFormat($request['mbirthdate']);
    if(isset($request['mstatus'])){
    $studentInfo->mstatus = $request['mstatus'];
    }
    $studentInfo->mcourse = $request['mcourse'];
    $studentInfo->mmobile = $request['mmobile'];
    $studentInfo->mlandline = $request['mlandline'];
    $studentInfo->mreligion = $request['mreligion'];
    $studentInfo->mnationality = $request['mnationality'];
    $studentInfo->mselfemployed = $request['mselfemployed'];
    $studentInfo->mFulljob = $request['mFulljob'];
    $studentInfo->mPartjob = $request['mPartjob'];
    if(isset($request['mposition'])){
    $studentInfo->mposition = $request['mposition'];
    }
    $studentInfo->mincome = $request['mincome'];
    $studentInfo->mcompany = $request['mcompany'];
    $studentInfo->mComAdd = $request['mComAdd'];
    $studentInfo->mofficePhone = $request['mOfficePhone'];
    $studentInfo->mfax = $request['mfax'];
    $studentInfo->memail = $request['memail'];
    
    if(isset($request['residence'])){
    $studentInfo->residence = $request['residence'];
    }
    if(isset($request['ownership'])){
    $studentInfo->ownership = $request['ownership'];
    }
    $studentInfo->numHouseHelp = $request['numHouseHelp'];
    if(isset($request['transportation'])){
    $studentInfo->transportation = $request['transportation'];
    }
    $studentInfo->carcount = $request['carcount']; 
    if(isset($request['haveComputer'])){
    $studentInfo->haveComputer = $request['haveComputer'];
    }
    if(isset($request['haveInternet'])){
    $studentInfo->haveInternet = $request['haveInternet'];
    }
    if(isset($request['haveComputer'])){
    $studentInfo->internetType = $request['internetType'];
    }
    
    $studentInfo->guardianname = $request['guardianname'];
    $studentInfo->guardianmobile = $request['guardianmobile'];
    $studentInfo->guardianrelationship = $request['guardianrelationship'];
    $studentInfo->save();
    
    return null;
}

public function createSibling(array $request){
    $numberofrow=10;

    for($counter = 1;$counter<=$numberofrow;$counter++){
        
    $working = Input::has('working'.$counter) ? 1 : 0;
    $studying = Input::has('studying'.$counter) ? 1 : 0;
    $dbti = Input::has('dbti'.$counter) ? 1 : 0;
    
    $sibling = new \App\Sibling();
    $sibling->idno = $request['idno'];
    $sibling->sort = $counter;
    $sibling->name = $request['sibling'.$counter];
    $sibling->birthdate = $this->dateFormat($request['siblingbday'.$counter]);
    if(isset($request['siblinggender'.$counter])){
    $sibling->gender = $request['siblinggender'.$counter];
    }
    if(isset($request['siblingstatus'.$counter])){
    $sibling->status = $request['siblingstatus'.$counter];
    }
    $sibling->studying = $studying;
    $sibling->working = $working;
    $sibling->dbti = $dbti;
    $sibling->where = $request['where'.$counter];
    $sibling->save();
    }
    return null;
}

public function updateSibling(array $request,$idno){
    $numberofrow=10;

    for($counter = 1;$counter<=$numberofrow;$counter++){
    $working = Input::has('working'.$counter) ? 1 : 0;
    $studying = Input::has('studying'.$counter) ? 1 : 0;
    $dbti = Input::has('dbti'.$counter) ? 1 : 0;
        
        $matchfields=["idno"=>$idno,"sort"=>$counter];
   
    $sibling = \App\Sibling::where($matchfields)->first();
    $sibling->sort = $counter;
    $sibling->name = $request['sibling'.$counter];
    $sibling->birthdate = $this->dateFormat($request['siblingbday'.$counter]);
    if(isset($request['siblinggender'.$counter])){
    $sibling->gender = $request['siblinggender'.$counter];
    }
    if(isset($request['siblingstatus'.$counter])){
    $sibling->status = $request['siblingstatus'.$counter];
    }
    $sibling->studying = $studying;
    $sibling->working = $working;
    $sibling->dbti = $dbti;
    $sibling->where = $request['where'.$counter];
    $sibling->save();
    }
    return null;
}
}