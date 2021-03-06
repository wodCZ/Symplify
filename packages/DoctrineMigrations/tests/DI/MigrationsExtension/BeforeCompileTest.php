<?php declare(strict_types=1);

namespace Zenify\DoctrineMigrations\Tests\DI\MigrationsExtension;

use Arachne\EventDispatcher\DI\EventDispatcherExtension;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use Nette\DI\ServiceDefinition;
use Nette\DI\Statement;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;

final class BeforeCompileTest extends TestCase
{
    /**
     * @var MigrationsExtension
     */
    private $extension;

    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    protected function setUp()
    {
        $this->extension = new MigrationsExtension;

        $this->containerBuilder = new ContainerBuilder;
        $this->containerBuilder->parameters = ['appDir' => __DIR__];
        $this->containerBuilder->addDefinition('console', (new ServiceDefinition)->setClass(Application::class));

        $compiler = new Compiler($this->containerBuilder);
        $compiler->addExtension('eventDispatcher', new EventDispatcherExtension);

        $this->extension->setCompiler($compiler, 'migrations');
        $this->extension->loadConfiguration();
        $this->extension->beforeCompile();

        $this->containerBuilder->prepareClassList();
    }

    public function testSetConfigurationToCommands()
    {
        $executeCommandDefinition = $this->getDefinitionByType(ExecuteCommand::class);

        $this->matchDefinitionSetupStatement(
            $executeCommandDefinition->getSetup()[0],
            'setMigrationConfiguration',
            ['@' . Configuration::class]
        );
    }

    public function testLoadCommandsToApplication()
    {
        $applicationDefinition = $this->getDefinitionByType(Application::class);

        $this->assertCount(6, $applicationDefinition->getSetup());
        $this->matchDefinitionSetupStatement(
            $applicationDefinition->getSetup()[0],
            'add',
            ['@2_Doctrine_DBAL_Migrations_Tools_Console_Command_DiffCommand']
        );
    }

    /**
     * @param Statement $statement
     * @param string $entity
     * @param array $arguments
     */
    private function matchDefinitionSetupStatement(Statement $statement, $entity, array $arguments)
    {
        $this->assertSame($entity, $statement->getEntity());
        $this->assertSame($arguments, $statement->arguments);
    }

    /**
     * @param string $type
     * @return ServiceDefinition
     */
    private function getDefinitionByType($type)
    {
        return $this->containerBuilder->getDefinition($this->containerBuilder->getByType($type));
    }
}
