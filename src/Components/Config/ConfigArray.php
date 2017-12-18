<?php
namespace Modulus\Components\Config;

/**
 * Class ConfigArray
 * @package Modulus\Components\Config
 * @author Wesley Guthrie
 * @email therealwesleywmd@gmail.com
 */
class ConfigArray implements ConfigArrayInterface
{
    /** @var array */
    private $contents = [];

    /** @var string */
    protected $idGlue = ":";

    /**
     * {@inheritdoc}
     */
    public function __construct($defaults = [])
    {
        $this->contents = $defaults;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($id)
    {
        return (bool) ! is_null( $this->getRecursive($id,$this->contents) );
    }

    /**
     * {@inheritdoc}
     */
    public function get($id = null)
    {
        if( empty($id) ) {
            return $this->contents;
        }
        return $this->getRecursive($id,$this->contents);
    }

    /**
     * {@inheritdoc}
     */
    public function set($id, $value)
    {
        $id = $this->splitId($id);
        $new_contents = $this->setRecursive($id,$value);
        $this->replace($new_contents);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($array)
    {
        $this->contents = array_replace_recursive($this->contents, $array);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($id)
    {
        $parentId = $this->splitId($id);
        $removalId = array_pop($parentId);
        $parent =& $this->contents;
        foreach( $parentId as $key ) {
            $parent =& $parent[$key];
        }
        unset($parent[$removalId]);
    }

    /**
     * @param $id
     * @param $value
     *
     * @return array|null
     */
    private function getRecursive($id, $value)
    {
        $id = $this->splitId($id);
        $current = array_shift($id);
        if( ! isset($value[$current]) ) {
            return null;
        }
        if( count($id) > 0 ) {
            return $this->getRecursive($id,$value[$current]);
        }
        return $value[$current];
    }

    /**
     * @param $id
     * @param $value
     *
     * @return array
     */
    private function setRecursive($id, $value)
    {
        $current = array_pop($id);
        $value = [ $current => $value ];
        if( count($id) > 0 ) {
            return $this->setRecursive($id, $value);
        }
        return $value;
    }

    /**
     * @param $id
     * @param $contents
     *
     * @return array|null
     */
    private function wrapInParent($id, $contents)
    {
        $replace = array_pop($id);
        $parent = $this->getRecursive($id,$this->contents);
        $parent[$replace] = $contents;
        if( count($id) > 0 ) {
            return $this->wrapInParent($id, $parent);
        }
        return $parent;
    }

    /**
     * @param $id
     *
     * @return array
     */
    private function splitId($id)
    {
        return ( is_array($id) ? $id : explode($this->idGlue,$id) );
    }
}