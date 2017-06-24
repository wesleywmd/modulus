<?php
namespace Modulus;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Application extends ConsoleApplication
{
    /** @var ContainerBuilder $container */
    private $container;

    private $localModuleDir;
    private $composerModuleDir;

    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $this->container = new ContainerBuilder();
        $this->localModuleDir = getenv("HOME") . '/.modulus/modules/';
        $this->composerModuleDir = __DIR__;

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

        //$loader = new XmlFileLoader($this->container, new FileLocator(__DIR__.'/../../config'));
        //$loader->load('services.xml');
    }

    private function loadComposerModules()
    {

    }
}