<?php

namespace Ibrows\DeployBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class IbrowsDeployExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('ibrows_deploy.server', $config['server']);
        $container->setParameter('ibrows_deploy.environment', $config['environment']);
        $container->setParameter('ibrows_deploy.basic_auth_users', $config['basic_auth_users']);
        $container->setParameter('ibrows_deploy.server_environments', $config['server_environments']);
        $container->setParameter('ibrows_deploy.immediate_process_strategies', $config['immediate_process_strategies']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
