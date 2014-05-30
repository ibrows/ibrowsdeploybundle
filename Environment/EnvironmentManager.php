<?php

namespace Ibrows\DeployBundle\Environment;

class EnvironmentManager implements EnvironmentManagerInterface
{
    /**
     * @var string
     */
    protected $environment;

    /**
     * @var string
     */
    protected $server;

    /**
     * @var array
     */
    protected $commands;

    /**
     * @param string $environment
     * @param string $server
     * @param array $commands
     */
    public function __construct($environment, $server, array $commands)
    {
        $this->environment = $environment;
        $this->server = $server;
        $this->commands = $commands;
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
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }
}