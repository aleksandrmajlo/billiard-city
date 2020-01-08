<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 18.11.2019
 * Time: 14:23
 */

namespace App\Services;



use App\Acts\Purchaseinvoice;
Use App\Bars\Ingredient;
use App\Stock;
class PurchaseinvoiceService
{
    // обновление продуктов и ингадиентов
    static  public  function UpdateStockIngr($act_id){

        $act=Purchaseinvoice::find($act_id);
        if($act->ingredients){
            foreach ($act->ingredients as $ingredient){
                $ing=Ingredient::find($ingredient->id);
                $ing->count=$ing->count+$ingredient->pivot->count;
                $ing->save();
            }
        }
        if($act->stocks){
            foreach ($act->stocks as $stock){
                $st=Stock::find($stock->id);
                $st->count=$st->count+$stock->pivot->count;
                $st->save();
            }
        }

        \App\Services\ActService::UpdateProductCount();

    }
}