<?php

namespace Ibrows\DeployBundle\Environment\Command;

class SymfonyCommand extends AbstractSymfonyCommand
{

    /**
     * @param array $args
     * @return string
     */
    protected function getCommand(array $args)
    {
        $args = $this->getArguments($args);
        if (!array_key_exists('command', $args)) {
            throw new \RuntimeException("Need command argument");
        }
        return $args['command'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'symfonycommand';
    }
}