<?php

namespace Ibrows\DeployBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeployCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ibrows:deploy')
            ->addArgument('environment', InputArgument::REQUIRED, 'Tag to deploy: test|integration')
            ->setDescription('Tags local a new test or integration tag in git and pushes to remote');
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

        $newNumber = $this->getNewTagNumber($env);

        $output->writeln('Tagging "'. $env .'" with number '. $newNumber .' (<info>'. $env .'_'. $newNumber .'</info>)');
        $output->writeln(shell_exec('git tag '. escapeshellarg($env.'_'. $newNumber).' && git push --tags'));
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