<?php

namespace App\Traits;

trait StringControl{
    
    public function write_escapeString($string){
        $find = array("&#39;","&quot;","&#92;");
        $repWith = array("'","\"","\\");
        
        return str_replace($find,$repWith,$string);
    }
    
    public function read_escapeString($string){
        $find = array("'","\"","\\");
        $repWith = array("&#39;","\&quot;","\&#92;");
        
        return str_replace($find,$repWith,$string);
    }
}

