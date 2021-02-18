<?php


namespace Trunkrs\SDK\Util;


class NullStripper
{
    public static function strip(array $array): array {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = NullStripper::strip($value);
            } else if (is_null($value)) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}