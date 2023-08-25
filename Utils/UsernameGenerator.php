<?php

namespace Host2x\Core\Utils;

class UsernameGenerator
{

    /**
     * Generate a random username
     *
     */
    public static function GenerateUsername(): string
    {
        $num1 = rand(10000, 99999);
        $num2 = rand(10000, 99999);
        $username = $num1 . $num2;
        $username = substr($username, 0, 8); // max length 8
        $username[0] = "u";

        return $username;
    }

}