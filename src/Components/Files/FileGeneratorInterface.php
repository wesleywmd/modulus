<?php
namespace Modulus\Components\Files;

use League\Flysystem\FilesystemInterface;
use Modulus\Components\Config\ConfigArrayInterface;
use Modulus\Components\Files\Adapters\FileAdapterInterface;

/**
 * Interface FileGeneratorInterface
 * @package Modulus\Components\Files
 * @author Wesley Guthrie
 * @email therealwesleywmd@gmail.com
 */
interface FileGeneratorInterface
{
    /**
     * @param \League\Flysystem\FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem);

    /**
     * @param \Modulus\Components\Files\Adapters\FileAdapterInterface $adapter
     */
    public function setAdapter(FileAdapterInterface $adapter);

    /**
     * @param string $path
     *
     * @return bool
     */
    public function purge($path);

    /**
     * @param                                                 $path
     * @param \Modulus\Components\Config\ConfigArrayInterface $strategy
     * @param bool                                            $purge
     *
     * @return mixed
     */
    public function write($path, ConfigArrayInterface $strategy, $purge = false );
}