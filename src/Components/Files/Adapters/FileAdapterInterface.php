<?php
namespace Modulus\Components\Files\Adapters;

use Modulus\Components\Config\ConfigArrayInterface;

/**
 * Interface FileAdapterInterface
 * @package Modulus\Components\Files\Adapters
 * @author Wesley Guthrie
 * @email therealwesleywmd@gmail.com
 */
interface FileAdapterInterface
{
    /**
     * @param \Modulus\Components\Config\ConfigArrayInterface $configArray
     *
     * @return mixed
     */
    public function render(ConfigArrayInterface $configArray);
}