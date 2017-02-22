<?php declare(strict_types=1);

namespace Symplify\EasyCodingStandard\SniffRunner\Legacy;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use Symplify\EasyCodingStandard\SniffRunner\Contract\Sniff\SniffInterface;

final class LegacyCompatibilityLayer
{
    /**
     * @var bool
     */
    private static $isAdded = false;

    public static function add(): void
    {
        if (self::$isAdded) {
            return;
        }

        self::setupClassAliases();
        self::ensureLineEndingsAreDetected();
        self::setupVerbosityToMakeLegacyCodeRun();
        new Tokens;

        self::$isAdded = true;
    }

    /**
     * Ensure this option is enabled or else line endings will not always
     * be detected properly for files created on a Mac with the /r line ending.
     */
    private static function ensureLineEndingsAreDetected(): void
    {
        ini_set('auto_detect_line_endings', 'true');
    }

    private static function setupVerbosityToMakeLegacyCodeRun(): void
    {
        if (! defined('PHP_CODESNIFFER_VERBOSITY')) {
            define('PHP_CODESNIFFER_VERBOSITY', 0);
        }
    }

    private static function setupClassAliases(): void
    {
        class_alias(SniffInterface::class, Sniff::class);
        class_alias(Sniff::class, 'PHP_CodeSniffer_Sniff');
        class_alias(File::class, 'PHP_CodeSniffer_File');
        class_alias(Tokens::class, 'PHP_CodeSniffer_Tokens');
    }
}
