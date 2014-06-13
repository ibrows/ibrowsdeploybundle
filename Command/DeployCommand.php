<?php

namespace Ibrows\DeployBundle\Command;

use Ibrows\DeployBundle\Server\ImmediateProcessManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeployCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ibrows:deploy')
            ->addArgument('environment', InputArgument::REQUIRED, 'Tag to deploy: test|integration')
            ->addOption('immediate', null, InputOption::VALUE_REQUIRED, 'Immediate execute deploy process on server')
            ->setDescription('Tags local a new test or integration tag in git and pushes to remote')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \RuntimeException
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $env = $input->getArgument('environment');

        if(!in_array($env, array('test', 'integration'))){
            throw new \RuntimeException('Only "test" and "integration" is allowed to tag/deploy');
        }

        $this->tag($env, $output);

        if($server = $input->getOption('immediate')){
            $this->immediate($env, $server, $output);
        }
    }

    /**
     * @param string $env
     * @param string $server
     * @param OutputInterface $output
     */
    protected function immediate($env, $server, OutputInterface $output)
    {
        $output->writeln('Immediate start deployment on '. $server);
        $this->getImmediateProcessManager()->execute($env, $server, $output, $this->getHelperSet());
    }

    /**
     * @param string $env
     * @param OutputInterface $output
     */
    protected function tag($env, OutputInterface $output)
    {
        $newNumber = $this->getNewTagNumber($env);
        $output->writeln('Tagging "'. $env .'" with number '. $newNumber .' (<info>'. $env .'_'. $newNumber .'</info>)');
        $output->writeln(shell_exec('git tag '. escapeshellarg($env.'_'. $newNumber).' && git push --tags'));
    }

    /**
     * @return ImmediateProcessManagerInterface
     */
    protected function getImmediateProcessManager()
    {
        return $this->getContainer()->get('ibrows_deploy.server.immediateprocessmanager');
    }

    /**
     * @param string $env
     * @return int
     */
    protected function getNewTagNumber($env)
    {
        $tags = array_filter(explode("\n", shell_exec('git fetch --tags && git tag | grep '. escapeshellarg($env))));

        $numbers = array();
        foreach($tags as $tag){
            $number = substr($tag, strlen($env)+1);
            if(is_numeric($number)){
                $numbers[] = (int)$number;
            }
        }

        return $numbers ? max($numbers)+1 : 1;
    }
}