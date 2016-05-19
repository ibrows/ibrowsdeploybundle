<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;

class DoctrineDatabaseCreateCommand extends AbstractSymfonyCommand
{
    /**
     * @param array $args
     *
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(parent::getArguments(), array(
            'connection' => false,
            // default value for needSuccessfully is false because this task will fail when there is database already
            // option --if-not-exists was added in doctrine bundle but it's only included in symfony 3.0
            'needSuccessfully' => false
        ), $args);
    }

    /**
     * @param array $args
     * @return string
     */
    public function getCommand(array $args)
    {
        $cmd = 'doctrine:database:create --if-not-exists';

        if($args['connection']){
            $cmd .= sprintf(' --connection=%s', $args['connection']);
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
