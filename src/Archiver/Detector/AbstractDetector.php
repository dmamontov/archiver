<?php

namespace Archiver\Detector;

use Archiver\Writer\AbstractWriter;

/**
 * Class AbstractDetector.
 */
abstract class AbstractDetector
{
    abstract public function detectWriter(array $options): AbstractWriter;

    protected function isUnix(): bool
    {
        return !$this->isWindows();
    }

    protected function isWindows(): bool
    {
        return 'WIN' === strtoupper(substr(PHP_OS, 0, 3));
    }
}
