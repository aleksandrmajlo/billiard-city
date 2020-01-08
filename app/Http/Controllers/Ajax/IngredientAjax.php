<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 12.11.2019
 * Time: 10:09
 */

namespace App\Http\Controllers\Ajax;
use App\Bars\Ingredient;
use App\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class IngredientAjax extends Controller
{
     public function get(Request $request){
         $results=[];
         $id=$request->id;
         $results['ingredients']=[];
         if($id!=='-1'){
            $ingredients=Stock::find($id)->ingredients;
            foreach ($ingredients as $ingredient){
                $results['ingredients'][]=['id'=>$ingredient->id,'count'=>$ingredient->pivot->count];
            }
         }
         $results['ing']=Ingredient::all()->pluck('title','id');

         return response()->json([
             'success'=>true,
             'results' => $results,
         ], 200);
     }
}