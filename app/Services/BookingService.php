<?php

namespace App\Services;
use App\Customer;
use App\Reservation;
use App\Table;
use Carbon\Carbon;

class BookingService
{
     public static function add($data){

         $reservation = new Reservation();
         $reservation->id_table = $data['table'];

         $reservation->id_user = -1;

         // поскольку телефон уникален-проверяем или существует такой пользователь
         $customerFind=BookingService::FindCustomerByPhone($data['phone']);
         if($customerFind){
             $reservation->id_customers = $customerFind;
         }else{
             //добавляем пользователя
             $customer=new Customer;
             $customer->name="";
             $customer->surname="";
             $customer->phone=$data['phone'];
             $customer->email=$data['email'];
             $customer->save();
             $reservation->id_customers= $customer->id;
         }

         $reservation->name = "";
         $reservation->lastname ="";
         $reservation->phone = $data['phone'];

         $reservation->email = $data['email'];
         $reservation->booking_from = $data['date_start'];
         $reservation->booking_before = $data['date_end'];;
         $reservation->book = 1;
         $reservation->source = "application";
         $reservation->status = $data['status'];

         $table = Table::find($data['table']);
         $reservation->table_number = $table->number;

         $reservation->save();
         return $reservation->id;

     }

     public static function FindCustomerByPhone($phone){

         $customer_id=false;
          if(is_string($phone)){
              /*
                * телефон храним в таком виде 0677855392
               */
              $phone=self::ValidPhone($phone);
              $customer=Customer::where('phone',$phone)->first();
              if($customer)$customer_id=$customer->id;
          }
         return $customer_id;
     }

     // телефон к одному виду
     public static function ValidPhone($phone){
         $phone=str_replace('+',"",$phone);
         $firstCharacter = $phone[0];
         if($firstCharacter=="3"){
             $phone = substr($phone, 1);
         }
         return $phone;
     }


}