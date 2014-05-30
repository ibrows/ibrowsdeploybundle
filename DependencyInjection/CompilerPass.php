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
        foreach($container->getParameter('ibrows_deploy.tags') as $commandServiceId => $tags){
            $definition = $container->getDefinition($commandServiceId);
            foreach($tags['servers'] as $server){
                $definition->addMethodCall('addServer', array($server));
            }
            foreach($tags['environments'] as $environment){
                $definition->addMethodCall('addEnvironment', array($environment));
            }
        }

        $environmentManagerDefinition = $container->getDefinition('ibrows_deploy.environment.manager');
        foreach($this->findSortedByPriorityTaggedServiceIds($container, 'ibrows_deploy.command') as $serviceId => $tags){
            $environmentManagerDefinition->addMethodCall('addCommand', array(new Reference($serviceId)));
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param string $tagName
     * @return array
     */
    protected function findSortedByPriorityTaggedServiceIds(ContainerBuilder $container, $tagName)
    {
        $taggedServices = $container->findTaggedServiceIds($tagName);

        uasort($taggedServices, function($a, $b) {
            $a = isset($a[0]['priority']) ? $a[0]['priority'] : 0;
            $b = isset($b[0]['priority']) ? $b[0]['priority'] : 0;
            return $a > $b ? -1 : 1;
        });

        return $taggedServices;
    }
}