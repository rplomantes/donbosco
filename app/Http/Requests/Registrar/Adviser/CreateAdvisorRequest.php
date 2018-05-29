<?php

namespace App\Http\Requests\Registrar\Adviser;

use App\Http\Requests\Request;

class CreateAdvisorRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $allowd_access_level = [env('USER_REGISTRAR')

        ];
        
        $user = \Auth::user()->accesslevel;
        
        if(in_array($user,$allowd_access_level)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname'=>'required',
            'lastname'=>'required',
        ];
    }
    
public function messages()
{
    return [
        'firstname.required' => 'A first name is required',
        'lastname.required'  => 'A last name is required',
    ];
}
}
