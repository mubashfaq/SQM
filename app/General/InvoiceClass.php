<?php

namespace App\General;

use DB;
use DateTime;

class InvoiceClass
{
    public static function CreateBy($user){
        $user_name = DB::table('users')->select('name')->where('id',$user)->first();
        if(!empty($user_name->name)){
            return '<span class="user-name" user-id="'.$user.'">'.$user_name->name.'</span>';
        }
        return '';
    }
    public static function convertDateToDDMMYYYY($inputDate) {
        $date = new DateTime($inputDate);
        return $date->format('d-m-Y');
    }
}
