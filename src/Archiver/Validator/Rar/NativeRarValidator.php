<?php

namespace Archiver\Validator\Rar;

use Archiver\Exception\RarException;
use Archiver\Options;
use Archiver\Rar;
use Archiver\Validator\ValidatorInterface;

/**
 * Class NativeRarValidator.
 */
class NativeRarValidator implements ValidatorInterface
{
    public static function validateWriter(Options $options, bool $return = false): bool
    {
        $result = (!$options->isCompression() || Rar::COMPRESSION_STORE === $options->getCompression())
            && !count(array_diff($options->keys(), ['force', 'compression']))
        ;

        if (!$result && !$return) {
            throw new RarException('Native writer does not support this configuration');
        }

        return $result;
    }
}
