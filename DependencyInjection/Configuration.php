<?php

namespace Ibrows\DeployBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ibrows_deploy');

        $rootNode
            ->children()
                ->scalarNode('environment')->cannotBeEmpty()->end()
                ->scalarNode('server')->cannotBeEmpty()->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
