<?php

namespace Ibrows\DeployBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ibrows\DeployBundle\Environment\EnvironmentManagerInterface;
use Symfony\Component\Process\Process;

class RunCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ibrows:deploy:run')
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
        $environmentManager = $this->getEnvironmentManager();

        foreach($environmentManager->getCommands() as $command){
            $output->writeln('Execute '. $command);
            $process = new Process($command);
            $process->run();

            $output->writeln($process->getOutput());
        }
    }

    /**
     * @return EnvironmentManagerInterface
     */
    protected function getEnvironmentManager()
    {
        return $this->getContainer()->get('ibrows_deploy.environment.manager');
    }
}