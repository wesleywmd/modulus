<?php
namespace Modulus\Components\Shell;


class ShellOperator
{
    protected $shellCommandFactory;

    protected $terminal;

    public function __construct(
        ShellCommandFactory $shellCommandFactory,
        Terminal $terminal
    ) {
       $this->shellCommandFactory = $shellCommandFactory;
       $this->terminal = $terminal;
    }

    public function isWindows()
    {
        return $this->terminal->getIsWindows();
    }

    public function exists($command)
    {
        $command = $this->shellCommandFactory->create($command);
        return $this->terminal->exists($command);
    }

    public function execute($command, $arguments = [], $options = [], $cwd = null)
    {
        $command = $this->shellCommandFactory->create($command, $arguments, $options);
        return $this->terminal->execute($command, $cwd);
    }

    public function interactive($command, $arguments = [], $options = [], $cwd = null)
    {
        $command = $this->shellCommandFactory->create($command, $arguments, $options);
        return $this->terminal->interactive($command, $cwd);
    }
}