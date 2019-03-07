<?php

/**
 * Generate a random string of charaters
 *
 * @param [boolean] $len
 * @param string
 */
function random_str($len)
{
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_";
    $result = "";
    for ($i = 0; $i < $len; $i++) {
        $result .= $chars[mt_rand(0, 63)];
    }
    return $result;
}
