<?php

namespace Ibrows\DeployBundle\Server;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

interface ImmediateProcessManagerInterface
{
    /**
     * @param string $serviceId
     * @param ImmediateProcessStrategyInterface $strategy
     */
    public function addStrategy($serviceId, ImmediateProcessStrategyInterface $strategy);

    /**
     * @param string $env
     * @param string $server
     * @param OutputInterface $output
     * @param HelperSet $helperSet
     */
    public function execute($env, $server, OutputInterface $output, HelperSet $helperSet);
}