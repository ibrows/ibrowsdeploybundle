<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ShellCommand extends AbstractCommand
{

    /**
     * @var array
     */
    protected $args = array();


    /**
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->args['command'] = $command;
    }

    /**
     * @param array $args
     * @throws \RuntimeException
     * @return string
     */
    public function getCommand(array $args)
    {
        $args = $this->getArguments($args);
        if(!array_key_exists('command',$args)){
            throw new \RuntimeException("Need command argument");
        }
        $command = $args['command'];
        return $command;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'shellcommand';
    }
}