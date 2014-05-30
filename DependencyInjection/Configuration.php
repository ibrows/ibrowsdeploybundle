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
                ->scalarNode('environment')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('server')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('tags')->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('servers')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('environments')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
