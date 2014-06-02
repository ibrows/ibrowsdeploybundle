<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;

abstract class AbstractSymfonyCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $kernelRootDir;

    /**
     * @var array
     */
    protected $symfonyRunEnvironments = array('prod');

    /**
     * @param string $kernelRootDir
     * @param array $symfonyRunEnvironments
     * @param int $timeout
     * @param string $phpExecutablePath
     */
    public function __construct($kernelRootDir, array $symfonyRunEnvironments = null, $timeout = null, $phpExecutablePath = null)
    {
        $this->kernelRootDir = $kernelRootDir;

        if($symfonyRunEnvironments){
            $this->symfonyRunEnvironments = $symfonyRunEnvironments;
        }

        parent::__construct($timeout, $phpExecutablePath);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getCommand();
    }

    /**
     * @param OutputInterface $output
     * @throws \RuntimeException
     * @return int
     */
    public function run(OutputInterface $output)
    {
        $php = escapeshellarg($this->getPhpExecutablePath());

        $return = 0;
        foreach($this->symfonyRunEnvironments as $env){
            $console = escapeshellarg($this->kernelRootDir.'/console');
            $cmd = $php .' '. $console .' '. $this->getCommand().' --env='. $env;
            if($code = $this->execute($cmd, $output)){
                $return = $code;
            }
        }
        return $return;
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    protected function getPhpExecutablePath()
    {
        if($this->phpExecutablePath){
            return $this->phpExecutablePath;
        }

        $phpFinder = new PhpExecutableFinder();
        if(!$phpPath = $phpFinder->find()){
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }
}