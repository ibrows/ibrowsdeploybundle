<?php

namespace Ibrows\DeployBundle;

use Ibrows\DeployBundle\DependencyInjection\CompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IbrowsDeployBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CompilerPass());
    }
}
