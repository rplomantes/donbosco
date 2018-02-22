<?php

namespace App\Http\Controllers\Accounting\Assesment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Book extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }
    
    function module_info(){
        return ['menu'=>2,'title'=>'Books'];
    }
    
    function index(){
        $levels = \App\CtrLevel::all();
        $selected = 2;
        $module_info = $this->module_info();
        
        return view("accounting.EnrollmentAssessment.chooseLevelStrand",compact('levels','selected','module_info'));
    }
    
    function create($level,$course = ""){
        $books = \App\CtrBook::where('level',$level)->where('strand',$course)->get();
        $module_info = $this->module_info();
        return view("accounting.EnrollmentAssessment.books.createbooks",compact('books','level','course','module_info'));
    }
    
}
