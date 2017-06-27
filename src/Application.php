<?php
namespace Modulus;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class Application extends ConsoleApplication
{
    /** @var ContainerBuilder $container */
    private $container;

    private $localModuleDir;
    private $composerModuleDir;

    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $this->container = new ContainerBuilder();
        $this->loader = require __DIR__ . '/../../../autoload.php';
        $this->localModuleDir = getenv("HOME") . '/.modulus/modules/';
        $this->composerModuleDir = __DIR__ . "../../../";

        $this->loadLocalModules();
        $this->loadComposerModules();

        parent::__construct($name, $version);
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        echo $this->localModuleDir . "\n";
        echo $this->composerModuleDir;
        parent::run($input,$output);
    }

    private function loadLocalModules()
    {
        if (is_dir($this->localModuleDir)) {
            if ($dir = opendir($this->localModuleDir)) {
                while (($file = readdir($dir)) !== false) {
                    // if file is a directory and doesn't start with a '.'
                    if( (filetype($this->localModuleDir . $file) === 'dir') && !(substr($file, 0, strlen('.')) === '.') ) {
                        if( is_file($this->localModuleDir . $file . '/modulus.xml') ) {
                            $this->loadModuleXmlFile($this->localModuleDir . $file);
                        }
                    }
                }
                closedir($dir);
            }
        }
    }

    private function loadComposerModules()
    {

    }

    private function loadModuleXmlFile($path)
    {
        $loader = new XmlFileLoader($this->container, new FileLocator($path));
        $loader->load('modulus.xml');
    }
}