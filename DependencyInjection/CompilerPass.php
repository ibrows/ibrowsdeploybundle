<?php

namespace Ibrows\DeployBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $environmentManagerDefinition = $container->getDefinition('ibrows_deploy.environment.manager');
        foreach($container->findTaggedServiceIds('ibrows_deploy.command') as $serviceId => $tags){
            $environmentManagerDefinition->addMethodCall('addCommand', array(new Reference($serviceId)));
        }

        $environmentManagerDefinition = $container->getDefinition('ibrows_deploy.server.immediateprocessmanager');
        foreach($container->findTaggedServiceIds('ibrows_deploy.immediateprocessstrategy') as $serviceId => $tags){
            $environmentManagerDefinition->addMethodCall('addStrategy', array($serviceId, new Reference($serviceId)));
        }
    }
}