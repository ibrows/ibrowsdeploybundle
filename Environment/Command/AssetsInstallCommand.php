<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;

class AssetsInstallCommand extends AbstractSymfonyCommand
{
    /**
     * @var string
     */
    protected $webDir = 'web';

    /**
     * @var bool
     */
    protected $symlink = false;

    /**
     * @param string $kernelRootDir
     * @param string $webDir
     * @param bool $symlink
     * @param int $timeout
     * @param string $phpExecutablePath
     * @param string $phpIni
     */
    public function __construct($kernelRootDir, $webDir = 'web', $symlink = false, $timeout = null, $phpExecutablePath = null, $phpIni = null)
    {
        $this->webDir = $webDir;
        $this->symlink = $symlink;
        parent::__construct($kernelRootDir, $timeout, $phpExecutablePath, $phpIni);
    }

    /**
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(parent::getArguments(), array(
            'webDir' => $this->webDir,
            'symlink' => $this->symlink
        ), $args);
    }

    /**
     * @param array $args
     * @return string
     */
    public function getCommand(array $args)
    {
        $cmd = 'assets:install';

        if($args['symlink']){
            $cmd .= ' --symlink';
        }

        if($args['webDir']){
            $cmd .= ' '. escapeshellarg($args['webDir']);
        }

        return $cmd;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'assetsinstall';
    }
}