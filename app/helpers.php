<?php

/**
 * Generate a random string of charaters
 *
 * @param [boolean] $len
 * @return string
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

/**
 * Join paths together into a string
 *
 * @param [array<string>] $args
 * @return string
 */
function path_join(...$paths)
{
    return preg_replace("#/+#", '/', join('/', $paths));
}
