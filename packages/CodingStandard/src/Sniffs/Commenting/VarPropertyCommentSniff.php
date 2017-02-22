<?php declare(strict_types=1);

namespace Symplify\CodingStandard\Sniffs\Commenting;

use Symplify\CodingStandard\TokenWrapper\ClassWrapper;
use Symplify\EasyCodingStandard\SniffRunner\Contract\File\FileInterface;
use Symplify\EasyCodingStandard\SniffRunner\Contract\Sniff\SniffInterface;

/**
 * Rules:
 * - Properties should have doc block comment.
 */
final class VarPropertyCommentSniff implements SniffInterface // extends AbstractVariableSniff
{
    /**
     * @return int[]
     */
    public function register(): array
    {
        return [T_CLASS];
    }

    public function process(FileInterface $file, int $position): void
    {
        $classWrapper = ClassWrapper::createFromFileAndPosition($file, $position);

        foreach ($classWrapper->getProperties() as $propertyWrapper) {
            dump($propertyWrapper->getName());

            if ( ! $propertyWrapper->hasDocBlock()) {
                $file->addError('Property should have docblock comment.', $position, self::class);
                continue;
            }

            if ($propertyWrapper->getDocBlock()->isSingleLine()) {
                $file->addError('Property should have docblock comment.', $position, self::class);
            }
        }
    }
}
