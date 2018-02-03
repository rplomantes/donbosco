<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RptCashreceiptBook extends Model
{
    public function customChunk($size){
        $chunks = [];
        $currntrows = 0;
        $group = array();
        
        foreach($this->items  as $items){
            if($size <= $currntrows+$items->row_count){
               $chunks = new static($group);
               unset($group);
               $currntrows = 0;
            }
            
            $currntrows = $currntrows+$items->row_count;
            $group[] = $items;
        }
        
        return new static($chunks);
    }
}
