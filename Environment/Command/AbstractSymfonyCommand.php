<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;

abstract class AbstractSymfonyCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $console;

    /**
     * @var string
     */
    protected $phpExecutable;

    /**
     * @param string $console
     * @param int $timeout
     * @param null $phpExecutable
     */
    public function __construct($console, $timeout = 300, $phpExecutable = null)
    {
        $this->console = $console;
        $this->phpExecutable = $phpExecutable;
        parent::__construct($timeout);
    }

    /**
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(parent::getArguments(), array(
            'console' => $this->console,
            'phpExecutable' => $this->phpExecutable ?: $this->getPhpExecutablePath()
        ), $args);
    }

    /**
     * @param array $args
     * @param OutputInterface $output
     * @return int|null
     */
    public function run(array $args, OutputInterface $output)
    {
        $args = $this->getArguments($args);

        $php = escapeshellarg($args['phpExecutable']);
        $console = escapeshellarg($args['console']);

        $cmd = $php .' '. $console .' '. $this->getCommand($args);

        if(isset($args['symfonyEnv'])){
            $cmd .= ' --env='. $args['symfonyEnv'];
        }



        if(isset($args['symfonyVerbose'])){
            $vcount = (int) $args['symfonyVerbose'];
            if($vcount > 0){
                $cmd .= ' -';
                while($vcount >0){
                    $cmd .= 'v';
                    --$vcount;
                }
            }
        }
        $callback = $this->getCallback($args,$output);
        return $this->execute($cmd, $callback);
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    protected function getPhpExecutablePath()
    {
        $phpFinder = new PhpExecutableFinder();
        if(!$phpPath = $phpFinder->find()){
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable or define over arguments and try again');
        }
        return $phpPath;
    }
}