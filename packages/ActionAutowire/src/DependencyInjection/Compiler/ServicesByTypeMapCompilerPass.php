<?php declare(strict_types=1);

namespace Symplify\ActionAutowire\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symplify\ActionAutowire\DependencyInjection\Container\ServicesByTypeMap;

final class ServicesByTypeMapCompilerPass implements CompilerPassInterface
{
    /**
     * @var ServicesByTypeMap
     */
    private $serviceMap;

    public function __construct(ServicesByTypeMap $servicesByTypeMap)
    {
        $this->serviceMap = $servicesByTypeMap;
    }

    public function process(ContainerBuilder $containerBuilder) : void
    {
        $parameterBag = $containerBuilder->getParameterBag();
        foreach ($containerBuilder->getDefinitions() as $serviceId => $definition) {
            if (! $this->isValidServiceDefinition($definition)) {
                continue;
            }

            $this->serviceMap->addService(
                $parameterBag->resolveValue($definition->getClass()),
                $serviceId
            );
        }
    }

    private function isValidServiceDefinition(Definition $definition) : bool
    {
        if ($definition->isAbstract()) {
            return false;
        }

        if (! $definition->isPublic()) {
            return false;
        }

        if (! $definition->getClass()) {
            return false;
        }

        return true;
    }
}
