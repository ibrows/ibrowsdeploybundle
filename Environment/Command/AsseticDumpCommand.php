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
     * @param string $kernelRootDir
     * @param string $writeTo
     * @param bool $force
     * @param int $timeout
     * @param string $phpExecutablePath
     */
    public function __construct($kernelRootDir, $writeTo = 'web', $force = false, $timeout = null, $phpExecutablePath = null)
    {
        $this->writeTo = $writeTo;
        $this->force = $force;
        parent::__construct($kernelRootDir, $timeout, $phpExecutablePath);
    }

    /**
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(parent::getArguments(), array(
            'writeTo' => $this->writeTo,
            'force' => $this->force
        ), $args);
    }

    /**
     * @param array $args
     * @return string
     */
    public function getCommand(array $args)
    {
        $cmd = 'assetic:dump';

        if($writeTo = $this->writeTo){
            $cmd .= ' '. escapeshellarg($writeTo);
        }

        if($this->force){
            $cmd .= ' --force';
        }

        return $cmd;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'asseticdump';
    }
}