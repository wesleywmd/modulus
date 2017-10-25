<?php
namespace Modulus\Components\Config;

/**
 * Class CachedConfig
 * @package Modulus\Components\Config
 * @author Wesley Guthrie
 * @email therealwesleywmd@gamil.com
 */
class CachedConfig implements CachedConfigInterface
{
    /** @var string */
    private $path;

    /** @var string */
    private $hash;

    /** @var \Modulus\Components\Config\ConfigArrayInterface */
    private $contents;

    /**
     * CachedConfig constructor.
     *
     * @param string $path
     */
    public function __construct($path = "")
    {
        $configPath = dirname($path);
        $this->path = $path;
        if( ! is_dir($configPath) ) {
            mkdir($configPath,0755,true);
        }
        if( ! file_exists($this->path)) {
            touch($this->path);
        }
        $this->load();
    }

    /**
     * {@inheritdoc}
     */
    public function exists($id)
    {
        $this->load();
        return $this->contents->exists($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        $this->load();
        return $this->contents->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($id)
    {
        $this->load();
        $this->contents->remove($id);
        $this->unload();
    }

    /**
     * {@inheritdoc}
     */
    public function replace($array)
    {
        $this->load();
        $this->contents->replace($array);
        $this->unload();
    }

    /**
     * {@inheritdoc}
     */
    public function set($id, $value)
    {
        $this->load();
        $this->contents->set($id, $value);
        $this->unload();
    }

    /**
     * @return bool
     */
    private function hasChanged()
    {
        return ( md5_file($this->path) !== $this->hash );
    }

    /**
     * @return bool
     */
    private function isLoaded()
    {
        return (bool) !is_null($this->hash);
    }

    private function load()
    {
        if( $this->hasChanged() || !$this->isLoaded() ) {
            $this->contents = new ConfigArray($this->loadFromFile());
            $this->hash = md5_file($this->path);
        }
    }

    private function loadFromFile()
    {
        $contents = file_get_contents($this->path);
        if( $contents === "" ) {
            return [];
        } else {
            return json_decode($contents,true);
        }
    }

    private function unload()
    {
        if( $this->hasChanged() ) {
            throw new \Exception("CachedConfig file has changed since it was loaded.");
        } elseif( !$this->isLoaded() ) {
            throw new \Exception("Unable to unload a CachedConfig file that has not been loaded.");
        }
        $json = json_encode($this->contents->get(), JSON_PRETTY_PRINT);
        file_put_contents($this->path, str_replace("\/","/", $json));
    }
}