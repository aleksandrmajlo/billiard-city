<?php


namespace App\Observers;

use App\Stock;

class StockObserver
{
   public function updating(Stock $stock){
       if($stock->count<0) $stock->count=0;
   }
}