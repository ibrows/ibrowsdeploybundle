<?php

namespace Ibrows\DeployBundle\Environment;

interface EnvironmentManagerInterface
{
    /**
     * @return string
     */
    public function getEnvironment();

    /**
     * @return string
     */
    public function getServer();

    /**
     * @return array
     */
    public function getCommands();
}