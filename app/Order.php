<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Order extends Model
{
   
    public function tableReservation()
    {
        return $this->hasMany('App\Reservation', 'id', 'reservation_id');
    }

    public function tablePause()
    {
        return $this->hasOne('App\Pause', 'order_id')->orderBy('id', 'DESC');
    }


    public function tableId()
    {
        return $this->hasOne('App\Table', 'id', 'table_id');
    }

    /*
     * корректные методы
     * те что выше удалить потом
     */
    public function table()
    {
        return $this->hasOne('App\Table', 'id', 'table_id');
    }

    public function pauses()
    {
        return $this->hasMany('App\Pause');
    }

    public function bars()
    {
        return $this->hasMany('App\Bar');
    }

    public function getActivepauseAttribute()
    {
        $pauses = DB::table('pauses')
            ->where('order_id', $this->id)
            ->WhereNull('end_pause')
            ->count();
        if ($pauses === 0) {
            return false;
        } else {
            return true;
        }
    }
    public function change()
    {
        return $this->belongsTo('App\Change', 'changes_id');
    }

    public function getIsbarAttribute(){
        if($this->type_bar == 1){
            return true;
        }
        return false;
    }

    public function getTypeAttribute(){
        $locale = app()->getLocale();
        $type=1;
        if($this->type_billiards == 1){
            $type=2;
        }
        if($locale=="ru"){
            if($type==1){
                return 'Бар';
            }
            if($type==2){
                return 'Бильярд';
            }
        }else{
            if($type==1){
                return 'Бар';
            }
            if($type==2){
                return 'Більярд';
            }
        }
        
    }
    public   function user()
    {
        return $this->belongsTo('App\User');
    }
    public   function customer()
    {
        return $this->belongsTo('App\Customer');
    }
    //резерв
    public   function reservation()
    {
        return $this->belongsTo('App\Reservation');
    }

    // активные столы
    public function scopeTableactive($query)
    {
        return $query->where('type_bar', 1)
            ->where('closed', '=', null)
            ->orderBy('created_at', 'desc');

    }
    
}
