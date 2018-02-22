<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class GlobalHelper extends Controller
{
    static function escapeString($string){
        $find = array("&#39;","&quot;","&#92;");
        $repWith = array("'","\"","\\");
        
        return str_replace($find,$repWith,$string);
    }
}
