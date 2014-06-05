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
     * @var boolean
     */
    protected $output = true;

    /**
     * @var string
     */
    protected $runServer;

    /**
     * @var string
     */
    protected $runEnvironment;

    /**
     * @param int $timeout
     */
    public function __construct($timeout = 300)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return boolean
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $server
     */
    public function setRunServer($server)
    {
        $this->runServer = $server;
    }

    /**
     * @param string $environment
     */
    public function setRunEnvironment($environment)
    {
        $this->runEnvironment = $environment;
    }

    /**
     * @param array $args
     * @param OutputInterface $output
     * @return int|null
     */
    public function run(array $args, OutputInterface $output)
    {
        $args = $this->getArguments($args);
        $callback = $this->getCallback($args,$output);
        return $this->execute($this->getCommand($args, $callback));
    }

    protected function  getCallback($args, OutputInterface $output ){
        $callback = null;
        if($args['output']) {
            $callback = function($type,$buffer) use ($output) { $output->write($buffer);};
        }
        return $callback;
    }

    /**
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(array(
            'timeout' => $this->getTimeout(),
            'output' => $this->getOutput(),
        ), $args);
    }

    /**
     * @param string $cmd
     * @param callable $callback
     * @throws \RuntimeException
     * @return int|null
     */
    protected function execute($cmd, $callback = null)
    {
        $process = new Process($cmd, null, null, null, $this->getTimeout());
        $process->run($callback);

        if(!$process->isSuccessful()){
            throw new \RuntimeException(sprintf('An error occurred when executing the "%s" command.', $cmd));
        }

        return $process->getExitCode();
    }

    /**
     * @return int
     */
    protected function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @return string
     */
    protected function getRunEnvironment()
    {
        return $this->runEnvironment;
    }

    /**
     * @return string
     */
    protected function getRunServer()
    {
        return $this->runServer;
    }

    /**
     * @param array $args
     * @return string
     */
    abstract protected function getCommand(array $args);
}