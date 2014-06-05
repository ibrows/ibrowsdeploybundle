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
     * @param string $kernelRootDir
     * @param string $webDir
     * @param int $timeout
     * @param string $phpExecutablePath
     */
    public function __construct($kernelRootDir, $webDir = 'web', $timeout = null, $phpExecutablePath = null)
    {
        $this->webDir = $webDir;
        parent::__construct($kernelRootDir, $timeout, $phpExecutablePath);
    }

    /**
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(parent::getArguments(), array(
            'webDir' => $this->webDir,
        ), $args);
    }

    /**
     * @param array $args
     * @return string
     */
    public function getCommand(array $args)
    {
        $cmd = 'assets:install';

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