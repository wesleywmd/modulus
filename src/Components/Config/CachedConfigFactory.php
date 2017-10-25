<?php
namespace Modulus\Components\Config;

/**
 * Class CachedConfigFactory
 * @package Modulus\Components\Config
 * @author Wesley Guthrie
 * @email therealwesleywmd@gamil.com
 */
class CachedConfigFactory
{
    /**
     * @param $path
     *
     * @return \Modulus\Components\Config\CachedConfig
     */
    public function create($path)
    {
        return new CachedConfig($path);
    }
}