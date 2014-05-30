<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandInterface
{
    /**
     * @param OutputInterface $output
     * @return void
     */
    public function run(OutputInterface $output);

    /**
     * @param string $server
     * @param string $environment
     * @return bool
     */
    public function accept($server, $environment);

    /**
     * @return string
     */
    public function getName();
}