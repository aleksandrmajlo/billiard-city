<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 07.01.2020
 * Time: 13:04
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class PrintController extends Controller
{
   public  function index(){
          $printsetting=\App\Printsetting::find(1);
          return view('printsetting',['printsetting'=>$printsetting]);
   }

   public function update(Request $request){
       $printsetting=\App\Printsetting::find(1);
       $printsetting->port=$request->port;
       $printsetting->ip=$request->ip;
       $printsetting->save();
       return redirect('/aprint')->with('status', 'Edit!');

   }
}