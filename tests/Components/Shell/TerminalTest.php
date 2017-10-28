<?php
/**
 * Created by PhpStorm.
 * User: weswe
 * Date: 10/28/2017
 * Time: 12:33 AM
 */

use Modulus\Components\Shell\Terminal;
use PHPUnit\Framework\TestCase;

class TerminalTest extends TestCase
{
    /**
     * @test
     */
    public function testTerminal()
    {
        $terminal = new Terminal();
        $this->assertTrue($terminal->exists(new \Modulus\Components\Shell\ShellCommand("php")));
    }
}
