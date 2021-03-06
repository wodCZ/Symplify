<?php declare(strict_types=1);

namespace Symplify\EasyCodingStandard\Application\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symplify\EasyCodingStandard\Exception\Configuration\SourceNotFoundException;

final class RunApplicationCommand
{
    /**
     * @var array
     */
    private $sources = [];

    /**
     * @var bool
     */
    private $isFixer = false;

    /**
     * @var array
     */
    private $jsonConfiguration = [];

    public static function createFromInputAndData(InputInterface $input, array $data) : self
    {
        return new self($input->getArgument('source'), $input->getOption('fix'), $data);
    }

    public static function createFromSourceFixerAndData(array $source, bool $isFixer, array $data) : self
    {
        return new self($source, $isFixer, $data);
    }

    private function __construct(array $source, bool $isFixer, array $jsonConfiguration)
    {
        $this->setSources($source);
        $this->isFixer = $isFixer;
        $this->jsonConfiguration = $jsonConfiguration;
    }

    public function getSources() : array
    {
        return $this->sources;
    }

    public function getJsonConfiguration() : array
    {
        return $this->jsonConfiguration;
    }

    public function isFixer() : bool
    {
        return $this->isFixer;
    }

    public function getSniffs() : array
    {
        return $this->jsonConfiguration['php-code-sniffer']['sniffs'] ?? [];
    }

    public function getFixers() : array
    {
        return $this->jsonConfiguration['php-cs-fixer']['fixers'] ?? [];
    }

    private function setSources(array $sources) : void
    {
        $this->ensureSourceExists($sources);
        $this->sources = $sources;
    }

    private function ensureSourceExists(array $sources) : void
    {
        foreach ($sources as $source) {
            if ( ! file_exists($source)) {
                throw new SourceNotFoundException(sprintf(
                    'Source "%s" does not exist.',
                    $source
                ));
            }
        }
    }
}
