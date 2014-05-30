<?php

namespace Ibrows\DeployBundle\Environment;

use Doctrine\Common\Collections\ArrayCollection;
use Ibrows\DeployBundle\Environment\Command\CommandInterface;

class EnvironmentManager implements EnvironmentManagerInterface
{
    /**
     * @var string
     */
    protected $server;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var CommandInterface[]|ArrayCollection
     */
    protected $commands;

    /**
     * @param string $server
     * @param string $environment
     */
    public function __construct($server, $environment)
    {
        $this->server = $server;
        $this->environment = $environment;
        $this->commands = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param CommandInterface $command
     */
    public function addCommand(CommandInterface $command)
    {
        $this->commands->add($command);
    }

    /**
     * @param CommandInterface $command
     */
    public function removeCommand(CommandInterface $command)
    {
        $this->commands->removeElement($command);
    }

    /**
     * @param CommandInterface $command
     * @return bool
     */
    public function hasCommand(CommandInterface $command)
    {
        return $this->commands->contains($command);
    }

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @param string $server
     * @param string $environment
     * @return CommandInterface[]
     */
    public function getCommands($server = null, $environment = null)
    {
        $server = $server ?: $this->server;
        $environment = $environment ?: $this->environment;

        foreach($this->commands as $command){
            if($command->accept($server, $environment)){
                yield $command;
            }
        }
    }
}