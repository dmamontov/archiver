<?php

namespace Archiver\Enum;

class Compression
{
    public const ORIGINAL = 0;

    public const VERY_LOW = 1;

    public const LOW = 2;

    public const NORMAL = 3;

    public const HIGH = 4;

    public const VERY_HIGH = 5;

    public const LIST = [
        self::ORIGINAL,
        self::VERY_LOW,
        self::LOW,
        self::NORMAL,
        self::HIGH,
        self::VERY_HIGH,
    ];
}
