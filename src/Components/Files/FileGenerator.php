<?php
namespace Modulus\Components\Files;

use League\Flysystem\FilesystemInterface;
use Modulus\Components\Config\ConfigArrayInterface;
use Modulus\Components\Files\Adapters\FileAdapterInterface;

class FileGenerator
{
    /** @var \League\Flysystem\FilesystemInterface */
    private $filesystem;

    /** @var \Modulus\Components\Files\Adapters\FileAdapterInterface */
    private $adapter;

    /**
     * FileGenerator constructor.
     *
     * @param \League\Flysystem\FilesystemInterface|null                   $filesystem
     * @param \Modulus\Components\Files\Adapters\FileAdapterInterface|null $fileAdapter
     */
    public function __construct(
        FilesystemInterface $filesystem = null,
        FileAdapterInterface $fileAdapter = null
    ) {
        $this->filesystem = $filesystem;
        $this->adapter = $fileAdapter;
    }

    /**
     * @param mixed $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param mixed $adapter
     */
    public function setAdapter(FileAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function purge($path)
    {
        $this->verifySetup();

        if( $this->filesystem->has($path) ) {
            $this->filesystem->delete($path);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param                                                 $path
     * @param \Modulus\Components\Config\ConfigArrayInterface $strategy
     * @param bool                                            $purge
     *
     * @return bool
     */
    public function write($path, ConfigArrayInterface $strategy, $purge = false )
    {
        $this->verifySetup();

        if( $this->filesystem->has($path) && (bool) $purge ) {
            $this->filesystem->delete($path);
            $return = true;
        } else {
            $return = false;
        }

        $this->filesystem->write($path,$this->adapter->render($strategy));

        return $return;
    }

    private function verifySetup()
    {
        if( is_null($this->filesystem) || is_null($this->adapter) ) {
            throw new \Exception("Generator is not set up properly");
        }
    }

}