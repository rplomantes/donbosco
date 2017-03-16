<?php

namespace App\Http\Controllers\Miscellaneous;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Redirect;

class BookController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function index($idno){
        $books = DB::Select("Select id,status,description from ledgers where idno='$idno' and accountingcode = '440400'");
        
        return view("book.studentbooks",compact('books','idno'));
    }
    
    function updatebooks(Request $request){
        
        
        foreach($request->input('book') as $key=>$value){
            $book = \App\Ledger::find($key);
            $book->status = 2;
            $book->save();
            
        }
        
        return Redirect::back();
    }
}
