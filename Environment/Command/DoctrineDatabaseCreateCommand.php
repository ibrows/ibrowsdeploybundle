<?php

namespace Ibrows\DeployBundle\Environment\Command;

class DoctrineDatabaseCreateCommand extends AbstractSymfonyCommand
{
    /**
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(parent::getArguments(), array(
            'connection' => false
        ), $args);
    }

    /**
     * @param array $args
     * @return string
     */
    public function getCommand(array $args)
    {
        $cmd = 'doctrine:database:create';

        if($args['connection']){
            $cmd .= ' --connection='.escapeshellarg($args['connection']);
        }

        return $cmd;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'doctrinedatabasecreate';
    }
}
