<?php

namespace Archiver\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Exists.
 */
class File extends Constraint
{
    public const NOT_EXISTS = 'a12b1edf-da16-43d7-aeea-80a4da4f310c';
    public const NOT_FILE = '386381b1-e2b1-4e4d-884e-51f3cb2e829d';
    public const NOT_DIRECTORY = '09b38d10-95ef-485c-a770-ab72a0199e98';
    public const NOT_READABLE = 'baa4c20b-af2c-461b-9c22-131bdefca0f4';
    public const NOT_WRITABLE = 'e0699b37-eb2a-4397-aec7-6c80abcd4734';

    /**
     * @var array
     */
    public array $messages = [
        self::NOT_EXISTS => '{{ value }} not found',
        self::NOT_FILE => '{{ value }} is not a file',
        self::NOT_DIRECTORY => '{{ value }} is not a directory',
        self::NOT_READABLE => '{{ value }} cannot be read',
        self::NOT_WRITABLE => '{{ value }} not writable',
    ];

    /**
     * @var bool
     */
    public bool $exists = true;

    /**
     * @var bool
     */
    public bool $readable = false;

    /**
     * @var bool
     */
    public bool $writable = false;

    /**
     * @var bool
     */
    public bool $isDir = false;

    /**
     * @var array
     */
    protected static $errorNames = [
        self::NOT_EXISTS => 'IS_NOT_EXISTS',
        self::NOT_FILE => 'NOT_FILE',
        self::NOT_DIRECTORY => 'NOT_DIRECTORY',
        self::NOT_READABLE => 'NOT_READABLE',
        self::NOT_WRITABLE => 'NOT_WRITABLE',
    ];
}
