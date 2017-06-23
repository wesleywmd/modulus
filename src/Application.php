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

    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $this->container = new ContainerBuilder();

        //$loader = new XmlFileLoader($this->container, new FileLocator(__DIR__.'/../../config'));
        //$loader->load('services.xml');

        parent::__construct($name, $version);
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        parent::run($input,$output);
    }
}