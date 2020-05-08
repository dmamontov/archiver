<?php

namespace Archiver\Validator;

use Archiver\Exception\PathException;
use Archiver\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class FileSystemValidator
{
    public const TYPE_FILE = 0;

    public const TYPE_DIRECTORY = 1;

    public static function isWrite(string $path, int $type = self::TYPE_FILE): void
    {
        $errors = Validation::createValidator()->validate($path, [
            new NotBlank(),
            new File([
                'writable' => true,
                'isDir' => self::TYPE_DIRECTORY === $type,
            ]),
        ]);

        if (0 < count($errors)) {
            throw new PathException((string) $errors);
        }
    }

    public static function isRead(string $path, int $type = self::TYPE_FILE): void
    {
        $errors = Validation::createValidator()->validate($path, [
            new NotBlank(),
            new File([
                'readable' => true,
                'isDir' => self::TYPE_DIRECTORY === $type,
            ]),
        ]);

        if (0 < count($errors)) {
            throw new PathException((string) $errors);
        }
    }
}
