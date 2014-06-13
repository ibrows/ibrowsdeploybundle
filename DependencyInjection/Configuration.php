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
                ->scalarNode('server')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('environment')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('basic_auth_users')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('user')->isRequired()->end()
                            ->scalarNode('pass')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('immediate_process_strategies')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('serviceid')->isRequired()->end()
                            ->arrayNode('options')
                                ->prototype('variable')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('server_environments')
                    ->prototype('array')
                        ->prototype('array')
                            ->prototype('array')
                                ->children()
                                    ->integerNode('priority')->isRequired()->end()
                                    ->arrayNode('args')
                                        ->prototype('variable')
        ;

        return $treeBuilder;
    }
}
