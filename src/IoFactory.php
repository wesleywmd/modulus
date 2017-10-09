<?php
/**
 * Created by PhpStorm.
 * User: weswe
 * Date: 10/9/2017
 * Time: 7:29 PM
 */

namespace Modulus;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IoFactory
{
    public function create(InputInterface $input, OutputInterface $output)
    {
        return new Io($input,$output);
    }
}