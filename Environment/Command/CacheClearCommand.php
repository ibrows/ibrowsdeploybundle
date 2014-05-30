<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends AbstractSymfonyCommand
{
    /**
     * @var array
     */
    protected $symfonyRunEnvironments = array('dev', 'prod');

    /**
     * @return string
     */
    public function getCommand()
    {
        return 'cache:clear';
    }
}