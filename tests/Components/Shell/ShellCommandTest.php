<?php
use Modulus\Components\Shell\ShellCommand;
use PHPUnit\Framework\TestCase;

class ShellCommandTest extends TestCase
{
    /**
     * @test
     */
    public function testSimpleCommand()
    {
        $command = new ShellCommand();
        $command->setCommand("vagrant")
            ->addArgument("up");
        $this->assertEquals("vagrant up", $command->toString());

        $command = new ShellCommand();
        $command->setCommand("vagrant")
            ->addArgument("plugin")
            ->addArgument("list");
        $this->assertEquals("vagrant plugin list", $command->toString());

        $command = new ShellCommand();
        $command->setCommand("vagrant")
            ->addArgument([ "plugin", "list" ]);
        $this->assertEquals("vagrant plugin list", $command->toString());
    }

    /**
     * @test
     */
    public function testSimpleCommandWithOptions()
    {
        $command = new ShellCommand();
        $command->setCommand("vagrant")
            ->addArgument("provision")
            ->addOption("provision-with","puppet");
        $this->assertEquals("vagrant provision --provision-with=\"puppet\"",$command->toString());

        $command = new ShellCommand();
        $command->setCommand("vagrant")
            ->addArgument("provision")
            ->addOption(["provision-with"=>"puppet"]);
        $this->assertEquals("vagrant provision --provision-with=\"puppet\"",$command->toString());

        $command = new ShellCommand();
        $command->setCommand("vagrant")
            ->addArgument("provision")
            ->addOption(["provision-with"=>"puppet"])
            ->addOption(["provision-with"=>"ntp"]);
        $this->assertEquals("vagrant provision --provision-with=\"puppet\" --provision-with=\"ntp\"",$command->toString());

        $command = new ShellCommand();
        $command->setCommand("vagrant")
            ->addArgument("provision")
            ->addOption(["provision-with"=>["puppet","ntp"]]);
        $this->assertEquals("vagrant provision --provision-with=\"puppet\" --provision-with=\"ntp\"",$command->toString());

        $command = new ShellCommand();
        $command->setCommand("vagrant")
            ->addArgument("provision")
            ->addOption("provision-with",["puppet","ntp"]);
        $this->assertEquals("vagrant provision --provision-with=\"puppet\" --provision-with=\"ntp\"",$command->toString());
    }

    /**
     * @test
     */
    public function testTimeCommand()
    {
        $command = new ShellCommand();
        $command->setCommand("vagrant")
            ->addArgument("up")
            ->addTime();
        $this->assertEquals("time vagrant up",$command->toString());
    }

}
