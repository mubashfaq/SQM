<?php

namespace App\General;

use App\Models\GiftCard;
use App\Models\GiftCardPurchases;
use DateTime;

class StaticFunctionsClass
{
    public static function generateUniqueCouponCode()
    {
        $existingCombinations7Digits = GiftCardPurchases::pluck('coupon_code')->toArray();
        $existingCombinations7DigitsInGiftCard = GiftCard::pluck('coupon_code')->toArray();
        $totalPossibleCombinations7Digits = 36 ** 7;
        $totalPossibleCombinations8Digits = 36 ** 8;

        if (count($existingCombinations7Digits) >= $totalPossibleCombinations7Digits) {
            if (count($existingCombinations7Digits) < $totalPossibleCombinations8Digits) {
                do {
                    $code = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8));
                } while (in_array($code, $existingCombinations7Digits) && in_array($code, $existingCombinations7DigitsInGiftCard));

                return $code;
            } else {
                do {
                    $code = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 7));
                } while (in_array($code, $existingCombinations7Digits) && in_array($code, $existingCombinations7DigitsInGiftCard));

                return $code;
            }
        } else {
            do {
                $code = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 7));
            } while (in_array($code, $existingCombinations7Digits) && in_array($code, $existingCombinations7DigitsInGiftCard));

            return $code;
        }
    }

    public static function gift_card_expiry($date, $days)
    {
        $dateObj = new DateTime($date);
        $dateObj->modify("+$days days");

        $newDate = $dateObj->format('Y-m-d');

        return $newDate;
    }
}
