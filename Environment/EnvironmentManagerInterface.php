<?php

namespace Ibrows\DeployBundle\Environment;

use Ibrows\DeployBundle\Environment\Command\CommandInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface EnvironmentManagerInterface
{
    /**
     * @param CommandInterface $command
     */
    public function addCommand(CommandInterface $command);

    /**
     * @param OutputInterface $output
     * @param string $server
     * @param string $environment
     */
    public function runCommands(OutputInterface $output, $server = null, $environment = null);
}