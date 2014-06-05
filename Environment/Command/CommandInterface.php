<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandInterface
{
    /**
     * @param array $args
     * @param OutputInterface $output
     * @return void
     */
    public function run(array $args, OutputInterface $output);

    /**
     * @return string
     */
    public function getName();
}