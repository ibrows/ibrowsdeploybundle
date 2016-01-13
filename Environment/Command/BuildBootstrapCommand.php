<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler;
use Symfony\Component\Console\Output\OutputInterface;

class BuildBootstrapCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $bootstrapDirectory;

    /**
     * BuildBootstrapCommand constructor.
     *
     * @param string $bootstrapDirectory
     */
    public function __construct(
        $bootstrapDirectory
    ) {
        $this->bootstrapDirectory = $bootstrapDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function run(array $args, OutputInterface $output)
    {
        $bootstrapDirectory = $this->bootstrapDirectory;
        if(isset($args['bootstrapDirectory'])) {
            $bootstrapDirectory = $args['bootstrapDirectory'];
        }

        ScriptHandler::doBuildBootstrap($bootstrapDirectory);

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommand(array $args)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bootstrap_build';
    }
}