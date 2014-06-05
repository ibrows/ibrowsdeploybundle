<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends AbstractSymfonyCommand
{
    /**
     * @param array $args
     * @return string
     */
    public function getCommand(array $args)
    {
        return 'cache:clear';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cacheclear';
    }
}