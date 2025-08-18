<?php

namespace App\General;

use DB;

class CollectionClass
{
    public static function getOptionValue($option, $items)
    {
        foreach ($items as $item) {
            if ($item->option === $option) {
                return $item->value;
            }
        }

        return ''; // Return null if option is not found
    }
    public static function check_active($active_status)
    {
        switch ($active_status) {
            case 0:
                return 'Draft';
                break;
            case 1:
                return 'Public';
                break;
            case 2:
                return 'Private';
                break;
            case 3:
                return 'Trash';
                break;
        }
    }
    public static function CreateBy($user)
    {
        $user_name = DB::table('users')->select('name')->where('id', $user)->first();
        if (!empty($user_name->name)) {
            return '<span class="user-name" user-id="' . $user . '">' . $user_name->name . '</span>';
        }
        return '';
    }

    public static function check_length($array)
    {
        $count = count($array);
        if ($count == 1) {
            foreach ($array as $collection) {
                if (count($collection->products) >= 1) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return true;
    }
}
