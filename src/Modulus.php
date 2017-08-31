<?php
/**
 * Created by PhpStorm.
 * User: weswe
 * Date: 8/31/2017
 * Time: 4:44 PM
 */

namespace Modulus;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class Modulus
{
    private $container;

    private $loader;

    private $config_path;

    public function __construct($path)
    {
        $this->config_path = $path . '/config';
        $this->container = new ContainerBuilder();
        if( ! is_dir($this->config_path) ) {
            throw new \Exception("Exception: Missing Directory [".$this->config_path."]\n");
        }
        $locator = new FileLocator($this->config_path);
        $this->loader = new XmlFileLoader($this->container, $locator);
    }

    public function get($id)
    {
        $this->container->get($id);
    }

    public function load($resource)
    {
        $this->loader->load($resource);
    }
}