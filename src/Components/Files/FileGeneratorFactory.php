<?php
namespace Modulus\Components\Files;

use League\Flysystem\FilesystemInterface;

/**
 * Class FileGeneratorFactory
 * @package Modulus\Components\Files
 * @author Wesley Guthrie
 * @email therealwesleywmd@gmail.com
 */
class FileGeneratorFactory
{
    /**
     * @param \League\Flysystem\FilesystemInterface          $filesystem
     * @param \Modulus\Components\Files\FileAdapterInterface $fileAdapter
     *
     * @return \Modulus\Components\Files\FileGenerator
     */
    public function create(FilesystemInterface $filesystem, FileAdapterInterface $fileAdapter)
    {
        return new FileGenerator($filesystem, $fileAdapter);
    }

    /**
     * @param \League\Flysystem\FilesystemInterface $filesystem
     * @param int                                   $inline
     * @param int                                   $indent
     * @param int                                   $flags
     *
     * @return \Modulus\Components\Files\FileGenerator
     */
    public function createYamlGenerator(FilesystemInterface $filesystem, $inline = 2, $indent = 4, $flags = 0)
    {
        return $this->create($filesystem, new YamlAdapter($inline,$indent,$flags));
    }

    /**
     * @param \League\Flysystem\FilesystemInterface $filesystem
     * @param                                       $templateLocation
     * @param \Twig_Environment                     $twigEnvironment
     *
     * @return \Modulus\Components\Files\FileGenerator
     */
    public function createTwigGenerator(FilesystemInterface $filesystem, $templateLocation, \Twig_Environment $twigEnvironment)
    {
        return $this->create($filesystem, new TwigAdapter($templateLocation, $twigEnvironment));
    }
}