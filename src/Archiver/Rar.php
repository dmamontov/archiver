<?php

namespace Archiver;

use Archiver\Detector\RarDetector;

/**
 * Class Rar.
 */
class Rar extends Archive
{
    /**
     * Rar constructor.
     */
    protected function __construct()
    {
        $this->detector = new RarDetector();
    }
}
