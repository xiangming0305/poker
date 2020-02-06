<?php

class Utils
{
    public static function randomPassword($max)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = strlen($characters);
        $output = '';
        for ($i = 0; $i < $max; $i++) {
            $output .= $characters[rand(0, $length - 1)];
        }

        return $output;
    }
}