<?php
namespace Modulus\Components\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Io
 * @package Modulus\Components\Style
 * @author Wesley Guthrie
 * @email therealwesleywmd@gmail.com
 */
class IoFactory
{
    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Modulus\Components\Style\Io
     */
    public function create(InputInterface $input, OutputInterface $output)
    {
        return new Io($input,$output);
    }
}