<?php

namespace App\General;

use App\Models\GeneralSettings;

class GeneralSettingsClass
{
    public static function getOptionValue($option, $items)
    {
        foreach ($items as $item) {
            if ($item->option === $option) {
                return $item->value;
            }
        }
        return '';
    }

    public static function getRequiredValue($option){
        $settings = GeneralSettings::get();
        foreach($settings as $setting){
            if($setting->option == $option){
                return $setting->value;
            }
        }
        return '';
    }
}
