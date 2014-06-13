<?php

namespace Ibrows\DeployBundle\Server;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

class ImmediateProcessManager implements ImmediateProcessManagerInterface
{
    /**
     * @var ImmediateProcessStrategyInterface[]
     */
    protected $strategies = array();

    /**
     * @var array
     */
    protected $immediateProcessStrategies = array();

    /**
     * @param array $immediateProcessStrategies
     */
    public function __construct(array $immediateProcessStrategies)
    {
        $this->immediateProcessStrategies = $immediateProcessStrategies;
    }

    /**
     * @param string $serviceId
     * @param ImmediateProcessStrategyInterface $strategy
     */
    public function addStrategy($serviceId, ImmediateProcessStrategyInterface $strategy)
    {
        $this->strategies[$serviceId] = $strategy;
    }

    /**
     * @param string $env
     * @param string $server
     * @param OutputInterface $output
     * @param HelperSet $helperSet
     */
    public function execute($env, $server, OutputInterface $output, HelperSet $helperSet)
    {
        if(!isset($this->immediateProcessStrategies[$server])){
            $output->writeln('No strategy found for server '. $server);
            return;
        }

        $serviceId = $this->immediateProcessStrategies[$server]['serviceid'];
        $options = $this->immediateProcessStrategies[$server]['options'];

        if(!isset($this->strategies[$serviceId])){
            $output->writeln('ServiceId '. $serviceId .' not found - tag service with "ibrows_deploy.immediateprocessstrategy"');
            return;
        }

        $this->strategies[$serviceId]->execute($env, $options, $output, $helperSet);
    }
}