<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePassword extends Controller
{
    function index(){
        return view('auth.changePass');
    }
    
     public function changePassword(Request $request){

            if (!(Hash::check($request->get('current-password'), \Auth::user()->password))) {
                // The passwords matches
                return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
            }

            if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
                //Current password and new password are same
                return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
            }

            $validatedData = $this->validator($request->all());
            
            if($validatedData->fails()){
                return view('auth.changePass')->withErrors($validatedData);
            }

            //Change Password
            $user = \Auth::user();
            $user->password = bcrypt($request->get('new-password'));
            $user->save();

            return redirect()->back()->with("success","Password changed successfully !");

        }
        
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
    }
}
