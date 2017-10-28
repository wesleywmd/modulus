<?php
namespace Modulus\Components\Shell;


interface TerminalInterface
{
    public function exists(ShellCommandInterface $command);

    public function getIsWindows();

    public function execute(ShellCommandInterface $command, $cwd = null);

    public function interactive(ShellCommandInterface $command, $cwd = null);
}