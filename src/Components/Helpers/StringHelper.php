<?php
namespace Modulus\Components\Helpers;

class StringHelper
{
    static function removeExtraWhiteSpace($str)
    {
        return preg_replace('/\s+/', ' ', trim($str) );
    }

    static function getLine($str,$line)
    {
        $str = explode("\n",$str);
        return ( isset($str[$line-1]) ? $str[$line-1] : null;
    }

    static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return $length === 0 || (substr($haystack, -$length) === $needle);
    }
}