<?php
namespace Modulus\Components\Config;

/**
 * Interface CachedConfigInterface
 * @package Modulus\Components\Config
 * @author Wesley Guthrie
 * @email therealwesleywmd@gamil.com
 */
interface CachedConfigInterface
{
    /**
     * Checks if $id exists.
     *
     * @param $id
     *
     * @return mixed
     */
    public function exists($id);

    /**
     * Gets the value of $id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function get($id);

    /**
     * Removes $id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function remove($id);

    /**
     * Replaces values with values in $array.
     *
     * @param $array
     *
     * @return mixed
     */
    public function replace($array);

    /**
     * Sets $id to $value.
     *
     * @param $id
     * @param $value
     *
     * @return mixed
     */
    public function set($id, $value);
}