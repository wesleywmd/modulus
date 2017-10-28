<?php
/**
 * Created by PhpStorm.
 * User: weswe
 * Date: 10/28/2017
 * Time: 1:05 AM
 */

namespace Modulus\Components\Shell;


class ShellCommandFactory
{
    public function create($command = "", $arguments = [], $options = [])
    {
        return new ShellCommand($command,$arguments,$options);
    }
}