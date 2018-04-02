<?php

namespace App\Http\Controllers\PortalControl;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AccountActivation extends Controller
{
    function index($idno){
        $record = \App\PortalVerificationRequest::where('idno',$idno)->first();
        
        if($record){
            $password = $record->password;
            $email = $record->email;
        }else{
            $password = $this->genPass();
            $this->activateAccount($idno, $password);
            $this->setPassword($idno, $password);
            $email = "";
        }
        
        return view('PortalControl.accountActivation',compact('idno','email','password'));
    }
    
    function activateAccount($idno,$password){
        $activatedAcct = new \App\PortalVerificationRequest();
        $activatedAcct->idno = $idno;
        $activatedAcct->password = $password;
        $activatedAcct->save();
    }
    
    function setPassword($idno,$password){
        $student = \App\User::where('idno',$idno)->first();
        if($student){
            $student->password = bcrypt($password);
            $student->save();
        }
    }
    
    function updatemail(){
        $idno = Input::get('id');
        $email = Input::get('email');
        
        $account = \App\PortalVerificationRequest::where('idno',$idno)->first();
        
        if($account){
            $account->email = $email;
            $account->save();
        }
        
    }
    

    
    function genPass(){
         $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
         $string = '';
         $max = strlen($characters) - 1;
         for ($i = 0; $i < 7; $i++) {
              $string .= $characters[mt_rand(0, $max)];
              
         }
         
         return $string;
    }
}
