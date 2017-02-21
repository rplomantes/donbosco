<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    
    public function __construct()
	{
		$this->middleware('auth');
	}
    
    //
        
    public function index(){
        $myid = \Auth::user()->id;
        $myname = \Auth::user()->lastname . ", " . \Auth::user()->firstname . " " . \Auth::user()->extentionname . " " . " " . \Auth::user()->middlename;
        $myaccess = \Auth::user()->accesslevel;
        $mystatus = \Auth::user()->status;
        $parameters = compact('myid','myname');
        if($mystatus == env('STATUS_OK')){
            switch ($myaccess){
               case env('USER_REGISTRAR');
                     $students=DB::Select("select a.idno, a.lastname, a.firstname, a.middlename, a.extensionname, a.gender from users as a, statuses as b where "
                           . "b.idno = a.idno and b.status='2' order by a.lastname, a.firstname");
                 
                  // $students = \App\User::where('accesslevel','0')->orderBy('lastname','firstname')->take(30)->get();
                   return view('registrar.index',compact('myid','myname','students'));
                   break;
           
               case env('USER_CASHIER');
               case env('USER_CASHIER_HEAD');  
                   $students=DB::Select("select a.idno, a.lastname, a.firstname, a.middlename, a.extensionname, a.gender from users as a, statuses as b where "
                           . "b.idno = a.idno and b.status='2' order by a.lastname, a.firstname");
                   //$students = \App\User::where('accesslevel','0')->orderBy('lastname','firstname')->take(30)->get();
                   return view('cashier.index',compact('myid','myname','students'));
                   break;
                   
                   
                 
               case env('USER_ACCOUNTING');
               case env('USER_ACCOUNTING_HEAD');
                   $students=DB::Select("select a.idno, a.lastname, a.firstname, a.middlename, a.extensionname, a.gender from users as a, statuses as b where "
                           . "b.idno = a.idno and b.status='2' order by a.lastname, a.firstname");
                   //$students = \App\User::where('accesslevel','0')->orderBy('lastname','firstname')->take(30)->get();
                   return view('accounting.index',compact('myid','myname','students')) ;                  
                  break;
               
              
               case env('USER_TVET_COOR');
                     $students = DB::Select("select lastname,firstname,middlename,extensionname,gender,users.idno,statuses.status as stat from users join statuses on statuses.idno = users.idno where statuses.department = 'TVET'");
                        return view('vincent.tvet.index',compact('students'));
                  break;
              
               case env('USER_TVET_ASST');
                     
                        return redirect('/sectiontvet');
                  break;                
            }
            
        } else {    
            return $mystatus;
        }
    }
    
    function getid(){
        
        return view('studentregister');
    }
    function addstudent(Request $data){
         \App\User::create([
            'idno' => $data['idno'],
            'lastname' => $data['lastname'],
            'firstname' => $data['firstname'],
            'middlename' => $data['middlename'],
            'gender' => $data['gender'],
            'extensionname' => $data['extensionname'],
            'accesslevel' => $data['accesslevel'],
            'email'=>$data['email'],
            'password' => bcrypt($data['password']),
        ]);
        
         return redirect('/');
    }
    
}
