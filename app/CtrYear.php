<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtrYear extends Model
{
    static function getYear($type){
        $year = "";
        $record = CtrYear::where('type',$type)->first();
        
        if($record){
            $year = $record->year;
        }
        
        return $year;
    }
}
