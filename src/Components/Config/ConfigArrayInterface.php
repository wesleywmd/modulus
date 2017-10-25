<?php
namespace Modulus\Components\Config;

/**
 * Interface ConfigArrayInterface
 * @package Modulus\Components\Config
 * @author Wesley Guthrie
 * @email therealwesleywmd@gmail.com
 */
interface ConfigArrayInterface
{
    /**
     * ConfigArrayInterface constructor.
     *
     * @param array $defaults
     */
    public function __construct($defaults = []);

    /**
     * Checks whether $id is set.
     *
     * @param $id
     *
     * @return mixed
     */
    public function exists($id);

    /**
     * Gets the value of $id.
     *
     * @param null $id
     *
     * @return mixed
     */
    public function get($id = null);

    /**
     * Sets the value of $id to $value.
     *
     * @param $id
     * @param $value
     *
     * @return mixed
     */
    public function set($id, $value);

    /**
     * Recursively replaces the values set in $array.
     *
     * @param $array
     *
     * @return mixed
     */
    public function replace($array);

    /**
     * Removes $id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function remove($id);
}