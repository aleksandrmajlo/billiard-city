<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 08.01.2020
 * Time: 13:59
 */

namespace App\Http\Controllers\Ajax;
use App\Http\Controllers\Controller;

class CusstomerAjax extends Controller
{
   public function get(){
       $customer=\App\Customer::all();
       return response()->json([
           'success'=>true,
           'customer' => $customer,
       ], 200);
   }
}