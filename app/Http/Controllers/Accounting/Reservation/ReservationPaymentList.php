<?php

namespace App\Http\Controllers\Accounting\Reservation;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReservationPaymentList extends Controller
{
    function view($sy){
        $reservations = \App\Credit::where('accountingcode',210400)->where('isreverse',0)->where('transactiondate','>=',$sy.'-08-01')->get();
        
        return view('accounting.Reservation.studentReservation',compact('reservations','sy'));
        
    }
}
