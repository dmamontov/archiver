<?php

namespace Archiver\Validator\Rar;

use Archiver\Collection\OptionsCollection;
use Archiver\Enum\Compression;
use Archiver\Exception\RarException;
use Archiver\Validator\ValidatorInterface;

/**
 * Class NativeRarValidator.
 */
class NativeRarValidator implements ValidatorInterface
{
    public static function validateWriter(OptionsCollection $options, bool $return = false): bool
    {
        $result = (!$options->isCompression() || Compression::ORIGINAL === $options->getCompression())
            && !count(array_diff($options->keys(), ['force', 'compression']))
        ;

        if (!$result && !$return) {
            throw new RarException('Native writer does not support this configuration');
        }

        return $result;
    }
}
