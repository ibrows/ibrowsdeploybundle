<?php

namespace Ibrows\DeployBundle\Environment;

use Doctrine\Common\Collections\ArrayCollection;
use Ibrows\DeployBundle\Environment\Command\CommandInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var array
     */
    protected $serverEnvironments;

    /**
     * @var array
     */
    protected $commands;

    /**
     * @param string $server
     * @param string $environment
     * @param array $serverEnvironments
     */
    public function __construct($server, $environment, array $serverEnvironments)
    {
        $this->server = $server;
        $this->environment = $environment;
        $this->serverEnvironments = $serverEnvironments;
    }

    /**
     * @param CommandInterface $command
     * @throws \RuntimeException
     */
    public function addCommand(CommandInterface $command)
    {
        $name = $command->getName();
        if(isset($this->commands[$name])){
            throw new \RuntimeException("Command with name ". $name ." already exists");
        }
        $this->commands[$command->getName()] = $command;
    }

    /**
     * @param OutputInterface $output
     * @param string $server
     * @param string $environment
     */
    public function runCommands(OutputInterface $output, $server = null, $environment = null)
    {
        $server = $server ?: $this->server;
        $environment = $environment ?: $this->environment;

        $output->writeln('IbrowsDeployBundle: Install for <info>'. $server .'/'. $environment .'</info>');

        if(!$commands = $this->getServerEnvironmentCommands($server, $environment)){
            $output->writeln('<info>No commands found for given server/environment</info>');
            return;
        }

        foreach($commands as $options){
            /** @var CommandInterface $command */
            $command = $options['command'];
            $output->writeln('<info>'. $command->getName() .'</info> <comment>'. ($options['args'] ? json_encode($options['args']) : null).'</comment>');

            $command->setRunServer($server);
            $command->setRunEnvironment($environment);

            $command->run($options['args'], $output);
        }
    }

    /**
     * @param $server
     * @param $environment
     * @throws \RuntimeException
     * @return array
     */
    protected function getServerEnvironmentCommands($server, $environment)
    {
        $commands = $this->getCommandsArray($server, $environment);

        $chain = array();
        foreach($commands as $commandName => $options){
            if(!isset($this->commands[$commandName])){
                throw new \RuntimeException("Command ". $commandName ." not available");
            }
            foreach($options as $option){
                $chain[] = array(
                    'command' => $this->commands[$commandName],
                    'priority' => $option['priority'],
                    'args' => isset($option['args']) && is_array($option['args']) ? $option['args'] : array()
                );
            }
        }

        usort($chain, function($a, $b){
            return $a['priority'] > $b['priority'];
        });

        return $chain;
    }

    /**
     * @param string $server
     * @param string $environment
     * @return array
     */
    protected function getCommandsArray($server, $environment)
    {
        $commands = array();
        $serverEnvironmentString = $server . '_' . $environment;
        foreach ($this->serverEnvironments as $key => $config) {
            if (1 === preg_match("/(\*!\[(.*)\])/", $key)) {
                $key = preg_replace("/(\*!\[(.*)\])/", "(?!.*$2)", $key);
            } elseif (1 === preg_match("/(\*)/", $key)) {
                $key = preg_replace("/(\*)/", ".*", $key);
            }
            if (preg_match("/$key/", $serverEnvironmentString)) {
                foreach ($config as $configName => $configArray) {
                    if (!array_key_exists($configName, $commands)) {
                        $commands[$configName] = [];
                    }
                    $commands[$configName] = array_merge($commands[$configName], $configArray);
                }
            }
        }

        return $commands;
    }
}
