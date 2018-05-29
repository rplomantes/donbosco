<?php

namespace App\Http\Controllers\Registrar\Adviser;

use App\Http\Requests\Registrar\Adviser\CreateAdvisorRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CreateAdvisor extends Controller
{
    function saveAdviser(CreateAdvisorRequest $request){
        $idno = $this->generateAdviserId($request->all());
        $password = strtolower($idno);
        
        $adviser = new \App\User();
        $adviser->title = $request->title;
        $adviser->firstname = $request->firstname;
        $adviser->middlename = $request->middlename;
        $adviser->lastname = $request->lastname;
        $adviser->extensionname = $request->extensionname;
        $adviser->accesslevel = env('USER_TEACHER');
        $adviser->idno = $idno;
        $adviser->password = $password;
        $adviser->save();
        
        //Automatically assign as Adviser
        $this->assignAsAdviser($idno);
        
        return redirect()->back();
    }
    
    function generateAdviserId($data){
        $idno = '';
        
        if($data['firstname'] != ""){
            $idno = $idno.substr($data['firstname'], 0,1);
        }
        if($data['middlename']){
            $idno = $idno.substr($data['middlename'], 0,1);
        }
        if($data['lastname']){
            $idno = $idno.$data['lastname'];
        }
        
        $idno = str_replace(' ','',$idno);
        return strtolower($idno);
    }
    
    function assignAsAdviser($adviser){
        $position  = \App\Position::where('position','Adviser')->first();
        
        if(!$position){
            $position  = new \App\Position();
            $position->position  = 'Adviser';
            $position->save();
        }
        
        $assignPosition = new \App\UsersPosition();
        $assignPosition->position = $position->id;
        $assignPosition->idno = $adviser;
        $assignPosition->save();
        
    }
}
