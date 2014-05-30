<?php

namespace Ibrows\DeployBundle\Environment;

use Ibrows\DeployBundle\Environment\Command\CommandInterface;

interface EnvironmentManagerInterface
{
    /**
     * @return string
     */
    public function getEnvironment();

    /**
     * @param string $environment
     */
    public function setEnvironment($environment);

    /**
     * @return string
     */
    public function getServer();

    /**
     * @param string $server
     */
    public function setServer($server);

    /**
     * @param CommandInterface $command
     */
    public function addCommand(CommandInterface $command);

    /**
     * @param CommandInterface $command
     */
    public function removeCommand(CommandInterface $command);

    /**
     * @param CommandInterface $command
     * @return bool
     */
    public function hasCommand(CommandInterface $command);

    /**
     * @param string $server
     * @param string $environment
     * @return CommandInterface[]
     */
    public function getCommands($server = null, $environment = null);
}