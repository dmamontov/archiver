<?php

namespace Archiver;

use Archiver\Detector\RarDetector;

/**
 * Class Rar.
 */
class Rar extends Archive
{
    public const COMPRESSION_STORE = 0;
    public const COMPRESSION_MINIMAL = 1;
    public const COMPRESSION_NORMAL = 2;
    public const COMPRESSION_DEFAULT = 3;
    public const COMPRESSION_BEST = 4;
    public const COMPRESSION_VERY_BEST = 5;

    /**
     * Rar constructor.
     */
    protected function __construct()
    {
        $this->detector = new RarDetector();
    }
}
