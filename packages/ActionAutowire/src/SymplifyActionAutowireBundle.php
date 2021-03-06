<?php declare(strict_types=1);

namespace Symplify\ActionAutowire;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symplify\ActionAutowire\DependencyInjection\Compiler\AddServiceLocatorCompilerPass;
use Symplify\ActionAutowire\DependencyInjection\Compiler\ServicesByTypeMapCompilerPass;
use Symplify\ActionAutowire\DependencyInjection\Container\ServicesByTypeMap;
use Symplify\ActionAutowire\DependencyInjection\Extension\ContainerExtension;

final class SymplifyActionAutowireBundle extends Bundle
{
    public function getContainerExtension() : ContainerExtension
    {
        return new ContainerExtension;
    }

    public function build(ContainerBuilder $containerBuilder) : void
    {
        $serviceByTypeMap = new ServicesByTypeMap;
        $containerBuilder->addCompilerPass(new ServicesByTypeMapCompilerPass($serviceByTypeMap));
        $containerBuilder->addCompilerPass(new AddServiceLocatorCompilerPass($serviceByTypeMap));
    }
}
