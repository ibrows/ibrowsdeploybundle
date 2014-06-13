<?php

namespace Ibrows\DeployBundle\Server;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

interface ImmediateProcessStrategyInterface
{
    /**
     * Starting the deploy process on a server
     * @param string $env
     * @param array $options
     * @param OutputInterface $output
     * @param HelperSet $helperSet
     * @return
     */
    public function execute($env, array $options, OutputInterface $output, HelperSet $helperSet);
}