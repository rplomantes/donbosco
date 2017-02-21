<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TORController extends Controller
{
    function index($idno){
       $student = \App\User::where('idno',$idno)->first();
       $info= \App\StudentInfo::where('idno',$idno)->first();
       $pdf = \App::make('dompdf.wrapper');
      // $pdf->setPaper([0, 0, 336, 440], 'portrait');
       $pdf->loadView("vincent.registrar.permanentRecord",compact('info','student'));
       return $pdf->stream();
       //return view('vincent.registrar.permanentRecord',compact('info','student'));
    }
}
