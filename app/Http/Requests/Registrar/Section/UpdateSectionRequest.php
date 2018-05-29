<?php

namespace App\Http\Requests\Registrar\Section;

use App\Http\Requests\Request;

class UpdateSectionRequest extends Request
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
            'sectionname'=>'required'
        ];
    }
}
