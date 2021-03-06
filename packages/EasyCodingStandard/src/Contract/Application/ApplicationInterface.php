<?php declare(strict_types=1);

namespace Symplify\EasyCodingStandard\Contract\Application;

use Symplify\EasyCodingStandard\Application\Command\RunApplicationCommand;

interface ApplicationInterface
{
    public function runCommand(RunApplicationCommand $command) : void;
}
