<?php


namespace App\Helpers;


class HelperFunctions
{
    /**
     * @param $items
     * @param $range - range[0] = min, range[1] = max
     * @return bool
     */
    public static function checkArrayIsInRange($items, $range)
    {
        foreach ($items as $item) {
            if ($item < $range[0] || $item > $range[1]) {
                return false;
            }
        }
        return true;
    }
}
