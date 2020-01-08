<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 14.11.2019
 * Time: 22:50
 */

namespace App\Http\Controllers;
use App\CategoryStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




class OrdermyController  extends Controller
{
    // открыть смену
    public  function open_order(Request $request){
        if($request->has('user_id')){
            if(Auth::user()->hasRole('manager')){
                return view('order.open_order_manager',[
                    'user_id'=>$request->user_id
                ]);
            }else{
                $ingredients=\App\Bars\Ingredient::orderBy('title')->get();
                $cats=CategoryStock::all();
                return view('order.open_order',[
                    'ingredients'=>$ingredients,
                    'cats'=>$cats,
                    'user_id'=>$request->user_id,
                    'kofeinyi_apparat_category_id'=>config('category.kofeinyi_apparat_category_id')
                ]);
            }
        }else{
            abort('404');
        }
    }
    // закрыть смену
    public  function close_order(Request $request){
        if($request->has('id')){

            $change = \App\Change::find($request->id);
            if (!is_null($change->stop)) {
                return redirect('/')->with('status', 'Cмена уже закрыта');
            }
            if(Auth::user()->hasRole('manager')){
                return view('order.close_order_manager',[
                    'id'=>$request->id
                ]);
            }else{
                $ingredients=\App\Bars\Ingredient::orderBy('title')->get();
                $cats=CategoryStock::all();
                return view('order.close_order',[
                    'ingredients'=>$ingredients,
                    'cats'=>$cats,
                    'kofeinyi_apparat_category_id'=>config('category.kofeinyi_apparat_category_id'),
                    'id'=>$request->id
                ]);
            }

        }else{
            abort('404');
        }
    }
}