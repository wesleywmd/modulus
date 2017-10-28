<?php
namespace Modulus\Components\Shell;

class ShellCommand implements ShellCommandInterface
{
    protected $command;

    protected $arguments;

    protected $options;

    protected $time;

    public function __construct($command = "", $arguments = [], $options = [])
    {
        $this->command = $command;
        $this->arguments = $arguments;
        $this->options = $options;
        $this->time = false;
    }

    /**
     * @param string $command
     *
     * @return $this
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }


    public function addArgument($arguments)
    {
        if( is_string($arguments) ) {
            $this->arguments[] = $arguments;
        } elseif( is_array($arguments) ) {
            array_walk($arguments,function($item) {
                $this->arguments[] = $item;
            });
        } else {
            throw new \Exception("unknown argument type");
        }
        return $this;
    }

    public function addOption($options,$value = null)
    {
        if( is_string($options) ) {
            $options = [$options=>$value];
        }
        if( is_array($options) ) {
            foreach( $options as $key => $item ) {
                if( isset($this->options[$key] ) ) {
                    if( ! is_array($this->options[$key]) ) {
                        $this->options[$key] = [ $this->options[$key] ];
                    }
                    $this->options[$key][] = $item;
                } else {
                    $this->options[$key] = $item;
                }
            }
        } else {
            throw new \Exception("unknown argument type");
        }
        return $this;
    }

    public function addTime($enabled = true)
    {
        $this->time = (bool) $enabled;
        return $this;
    }

    public function toString()
    {
        $command = ( (bool)$this->time ? "time" : "" );

        $command .= " " . $this->command;
        $command = trim($command);

        foreach( $this->arguments as $arg ) {
            $command .= " " . $arg;
        }
        $command = trim($command);

        foreach( $this->options as $key => $value ) {
            if( is_array($value) ) {
                foreach( $value as $v ) {
                    $command .= " --" . $key . "=\"" . $v . "\"";
                }
            } else {
                $command .= " --" . $key . "=\"" . $value . "\"";
            }
        }
        $command = trim($command);

        return $command;
    }

    public function getCommand()
    {
        return $this->command;
    }
}