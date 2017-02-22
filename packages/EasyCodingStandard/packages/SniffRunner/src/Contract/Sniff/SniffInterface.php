<?php declare(strict_types=1);

namespace Symplify\EasyCodingStandard\SniffRunner\Contract\Sniff;

use Symplify\EasyCodingStandard\SniffRunner\Contract\File\FileInterface;

interface SniffInterface
{
    /**
     * @return int[]
     */
    public function register(): array;

    public function process(FileInterface $file, int $position): void;
}
