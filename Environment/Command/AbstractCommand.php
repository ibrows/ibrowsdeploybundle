<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var int
     */
    protected $timeout = 300;

    /**
     * @param int $timeout
     */
    public function __construct($timeout = 300)
    {
        $this->timeout = $timeout;
    }

    /**
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(array(
            'timeout' => $this->timeout
        ), $args);
    }

    /**
     * @param array $args
     * @param OutputInterface $output
     * @return int|null
     */
    public function run(array $args, OutputInterface $output)
    {
        return $this->execute($this->getCommand($this->getArguments($args)));
    }

    /**
     * @param string $cmd
     * @param callable $callback
     * @throws \RuntimeException
     * @return int|null
     */
    protected function execute($cmd, $callback = null)
    {
        $process = new Process($cmd, null, null, null, $this->timeout);
        $process->run($callback);

        if(!$process->isSuccessful()){
            throw new \RuntimeException(sprintf('An error occurred when executing the "%s" command.', $cmd));
        }

        return $process->getExitCode();
    }

    /**
     * @param array $args
     * @return string
     */
    abstract protected function getCommand(array $args);
}