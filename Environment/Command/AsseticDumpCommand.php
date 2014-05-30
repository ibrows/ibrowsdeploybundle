<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;

class AsseticDumpCommand extends AbstractSymfonyCommand
{
    /**
     * @var string
     */
    protected $writeTo = 'web';

    /**
     * @var bool
     */
    protected $force = false;

    /**
     * @var array
     */
    protected $symfonyRunEnvironments = array('dev', 'prod');

    /**
     * @param string $kernelRootDir
     * @param string $writeTo
     * @param bool $force
     * @param array $symfonyRunEnvironments
     * @param int $timeout
     * @param string $phpExecutablePath
     */
    public function __construct($kernelRootDir, $writeTo = 'web', $force = false, array $symfonyRunEnvironments = null, $timeout = null, $phpExecutablePath = null)
    {
        $this->writeTo = $writeTo;
        $this->force = $force;
        parent::__construct($kernelRootDir, $symfonyRunEnvironments, $timeout, $phpExecutablePath);
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        $cmd = 'assetic:dump';
        if($writeTo = $this->writeTo){
            $cmd .= ' '. $writeTo;
        }
        if($this->force){
            $cmd .= ' --force';
        }
        return $cmd;
    }
}