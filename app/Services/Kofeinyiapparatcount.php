<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 01.12.2019
 * Time: 19:23
 */

namespace App\Services;
use App\Bars\Kofeinyiapparat;
use Illuminate\Support\Facades\DB;



class Kofeinyiapparatcount
{

    public static function add($count){
        DB::table('count_this_Kofeinyiapparat')
            ->where('id', 1)
            ->update(['count' => $count]);
    }

    public static function get(){
        $this_Kofeinyiapparat=DB::table('count_this_Kofeinyiapparat')
            ->where('id', 1)
            ->first();
        return $this_Kofeinyiapparat->count;
    }

    public static function addOrder($count){
        $this_count=self::get();
        self::add($this_count+$count);
    }

    public static function minusOrder($count){
        $this_count=self::get();
        self::add($this_count-$count);
    }

}