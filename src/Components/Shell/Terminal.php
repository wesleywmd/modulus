<?php
namespace Modulus\Components\Shell;

class Terminal implements TerminalInterface
{
    public function exists(ShellCommandInterface $command)
    {
        $command = $command->getCommand();
        if( $this->getIsWindows() ) {
            $command = new ShellCommand("WHERE",[$command, ">nul 2>&1 && ( echo 1 ) || ( echo 0 )"]);
        } else {
            $command = new ShellCommand("whereis",[$command]);
        }
        return (bool) (int) $this->execute($command);
    }

    /**
     * @return bool whether we are on a Windows OS
     */
    public function getIsWindows()
    {
        return strncasecmp(PHP_OS, 'WIN', 3)===0;
    }

    public function execute(ShellCommandInterface $command, $cwd = null)
    {
        $command = $command->toString();
        $descriptors = array(
            1 => array('pipe','w'),
            2 => array('pipe', $this->getIsWindows() ? 'a' : 'w'),
        );

        $process = proc_open($command, $descriptors, $pipes, $cwd);

        if( is_resource($process) ) {
            $stdOut = stream_get_contents($pipes[1]);
            $stdErr = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            $exitCode = proc_close($process);

            if ($exitCode!==0) {
                $error = $stdErr ? $stdErr : "Failed without error message: $command";
                return "[[{$exitCode}]] " . $error;
            }
        } else {
            return "[[1]] Could not run command $command";
        }
        return $stdOut;
    }

    public function interactive(ShellCommandInterface $command, $cwd = null)
    {
        if( ! $this->exists($command) ) {
            throw new \Exception("command [{$command->getCommand()}] does not exist");
        }

        $return_var = null;
        $stderr_ouput = array();
        $descriptorspec = array(
            // Must use php://stdin(out) in order to allow display of command output
            // and the user to interact with the process.
            0 => array('file', 'php://stdin', 'r'),
            1 => array('file', 'php://stdout', 'w'),
            2 => array('pipe', 'w'),
        );
        $pipes = array();
        $process = @proc_open($command->toString(), $descriptorspec, $pipes, $cwd);
        if (is_resource($process)) {
            // Loop on process until it exits normally.
            do {
                $status = proc_get_status($process);
                // If our stderr pipe has data, grab it for use later.
                if (!feof($pipes[2])) {
                    // We're acting like passthru would and displaying errors as they come in.
                    $error_line = fgets($pipes[2]);
                    echo $error_line;
                    $stderr_ouput[] = $error_line;
                }
            } while ($status['running']);
            // According to documentation, the exit code is only valid the first call
            // after a process is finished. We can't rely on the return value of
            // proc_close because proc_get_status will read the exit code first.
            $return_var = $status['exitcode'];
            proc_close($process);
            return [
                'exitcode' => $return_var,
                'stderr' => $stderr_ouput
            ];
        }
    }
}