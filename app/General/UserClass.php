<?php

namespace App\General;

class UserClass
{
    public static function check_role($role_id, $roles){
        foreach($roles as $role){
            if($role_id == $role->id){
                return $role->title;
            }
        }
    }
    public static function check_active($active_status){
        switch($active_status){
            case 0:
                return '<span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Inactive</span>';
            case 1:
                return '<span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Active</span>';
            default:
                return '<span class="badge bg-secondary-subtle text-success">Unknown</span>';
        }
    }
    
    public static function check_user($user_id, $users){
        foreach($users as $user){
            if($user->id == $user_id){
                return $user->name;
            }
        }
    }
    
    public static function getOptionValue($option, $items)
    {
        foreach ($items as $item) {
            if ($item->option === $option) {
            return $item->value;
            }
        }
        return ''; // Return null if option is not found
    } 

    public static function check_customer($user_id, $users){
        foreach($users as $user){
            if($user->id == $user_id){
                return $user->name;
            }
        }
    }
}
