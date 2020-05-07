<?php

namespace Archiver;

use Archiver\Exception\PathException;

/**
 * Class Validator.
 */
class Validator
{
    public const FS_TYPE_FILE = 'file';

    public const FS_TYPE_DIRECTORY = 'directory';

    public static function fs(string $path, string $type, bool $isWrite = false): void
    {
        if (!file_exists($path)) {
            throw new PathException(sprintf('%s not found.', ucfirst($type)));
        }

        if (!is_readable($path)) {
            throw new PathException(sprintf('%s cannot be read.', ucfirst($type)));
        }

        if ($isWrite && !is_writable($path)) {
            throw new PathException(sprintf('%s not writable.', ucfirst($type)));
        }

        if (self::FS_TYPE_DIRECTORY === $type && !is_dir($path)) {
            throw new PathException(sprintf('%s is not a directory.', ucfirst($type)));
        }

        if (self::FS_TYPE_FILE === $type && !is_file($path)) {
            throw new PathException(sprintf('%s is not a file.', ucfirst($type)));
        }
    }
}
