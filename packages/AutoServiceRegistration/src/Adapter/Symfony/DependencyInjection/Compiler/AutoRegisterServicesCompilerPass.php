<?php declare(strict_types=1);

namespace Symplify\AutoServiceRegistration\Adapter\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symplify\AutoServiceRegistration\Adapter\Symfony\Config\Definition\ConfigurationResolver;
use Symplify\AutoServiceRegistration\Naming\ServiceNaming;
use Symplify\AutoServiceRegistration\ServiceClass\ServiceClassFinder;

final class AutoRegisterServicesCompilerPass implements CompilerPassInterface
{
    /**
     * @var ServiceClassFinder
     */
    private $serviceFinder;

    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    public function __construct(ServiceClassFinder $serviceFinder)
    {
        $this->serviceFinder = $serviceFinder;
    }

    public function process(ContainerBuilder $containerBuilder) : void
    {
        $this->containerBuilder = $containerBuilder;

        $configurationResolver = new ConfigurationResolver($containerBuilder);

        $serviceClassesToRegister = $this->serviceFinder->findServicesInDirsByClassSuffix(
            $configurationResolver->getDirectoriesToScan(),
            $configurationResolver->getClassSuffixesToSeek()
        );

        $this->registerServicesToContainerBuilder($serviceClassesToRegister);
    }

    /**
     * @param string[] $serviceClasses
     */
    private function registerServicesToContainerBuilder(array $serviceClasses) : void
    {
        foreach ($serviceClasses as $serviceClass) {
            $id = ServiceNaming::createServiceIdFromClass($serviceClass);
            if ($this->containerBuilder->hasDefinition($id)) {
                continue;
            }

            $definition = $this->buildControllerDefinitionFromClass($serviceClass);
            $this->containerBuilder->setDefinition($id, $definition);
        }
    }

    private function buildControllerDefinitionFromClass(string $class) : Definition
    {
        $definition = new Definition($class);
        $definition->setAutowired(true);

        return $definition;
    }
}
