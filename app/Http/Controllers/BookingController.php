<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Reservation;
use App\Table;
use Carbon\Carbon;
use App\Customer;


class BookingController extends Controller
{
   public function index(){

       return view('booking');
   }
}