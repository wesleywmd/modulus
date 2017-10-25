<?php
namespace Modulus\Components\Config;

/**
 * Class ConfigArrayFactory
 * @package Modulus\Components\Config
 * @author Wesley Guthrie
 * @email therealrealwesleywmd@gmail.com
 */
class ConfigArrayFactory
{
    /**
     * @param array $defaults
     *
     * @return \Modulus\Components\Config\ConfigArray
     */
    public function create($defaults = [])
    {
        return new ConfigArray($defaults);
    }
}