<?php

namespace macchiato_academy\core\helpers;

class FlashMessage
{
    public static function get(string $key, $default = '')
    {
        if (isset($_SESSION['flash-message'])) {
            $value = $_SESSION['flash-message'][$key] ?? $default;
            unset($_SESSION['flash-message'][$key]);
        } else
            $value = $default;
        return $value;
    }
    public static function set(string $key, $value)
    {
        $_SESSION['flash-message'][$key] = $value;
    }
    public static function unset(string $key)
    {
        if (isset($_SESSION['flash-message']))
            unset($_SESSION['flash-message'][$key]);
    }
    public static function isSet(string $key)
    {
        return isset($_SESSION['flash-message'][$key]);
    }
}
