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
    protected $timeout;

    /**
     * @var string
     */
    protected $phpExecutablePath;

    /**
     * @var array
     */
    protected $servers = array();

    /**
     * @var array
     */
    protected $environments = array();

    /**
     * @param int $timeout
     * @param string $phpExecutablePath
     */
    public function __construct($timeout = null, $phpExecutablePath = null)
    {
        $this->timeout = $timeout;
        $this->phpExecutablePath = $phpExecutablePath;
    }

    /**
     * @param OutputInterface $output
     * @return int|null
     * @throws \RuntimeException
     */
    public function run(OutputInterface $output)
    {
        return $this->execute($this->getCommand(), $output);
    }

    /**
     * @return string
     */
    abstract protected function getCommand();

    /**
     * @param string $cmd
     * @param OutputInterface $output
     * @return int|null
     * @throws \RuntimeException
     */
    protected function execute($cmd, OutputInterface $output)
    {
        $process = new Process($cmd, null, null, null, $this->getTimeout());

        $process->run(function($type, $buffer)use($output){
            $output->writeln($buffer);
        });

        if(!$process->isSuccessful()){
            throw new \RuntimeException(sprintf('An error occurred when executing the "%s" command.', $cmd));
        }

        return $process->getExitCode();
    }

    /**
     * @param string $server
     */
    public function addServer($server)
    {
        $this->servers[] = $server;
    }

    /**
     * @param string $environment
     */
    public function addEnvironment($environment)
    {
        $this->environments[] = $environment;
    }

    /**
     * @param string $server
     * @param string $environment
     * @return bool
     */
    public function accept($server, $environment)
    {
        return in_array($server, $this->servers) && in_array($environment, $this->environments);
    }

    /**
     * @return int
     */
    protected function getTimeout()
    {
        return $this->timeout ?: 300;
    }
}