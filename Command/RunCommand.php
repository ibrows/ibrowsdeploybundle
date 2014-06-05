<?php

namespace Ibrows\DeployBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ibrows\DeployBundle\Environment\EnvironmentManagerInterface;
use Symfony\Component\Process\Process;

class RunCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ibrows:deploy:run')
            ->addOption('server', null, InputOption::VALUE_REQUIRED)
            ->addOption('environment', null, InputOption::VALUE_REQUIRED)
            ->setDescription('Runs the commands for given server and environment')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getEnvironmentManager()->runCommands($output, $input->getOption('server'), $input->getOption('environment'));
    }

    /**
     * @return EnvironmentManagerInterface
     */
    protected function getEnvironmentManager()
    {
        return $this->getContainer()->get('ibrows_deploy.environment.manager');
    }
}