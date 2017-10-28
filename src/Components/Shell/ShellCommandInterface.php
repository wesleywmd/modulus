<?php
namespace Modulus\Components\Shell;


interface ShellCommandInterface
{
    public function addTime();

    public function toString();

    public function addArgument($argument);

    public function addOption($option, $value = null);

    public function setCommand($command);

    public function getCommand();
}