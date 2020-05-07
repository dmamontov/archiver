<?php

namespace Archiver\Detector;

use Archiver\Exception\WriterException;
use Archiver\Process\RarProcess;
use Archiver\Writer\AbstractWriter;
use Archiver\Writer\Rar\NativeWriter;
use Archiver\Writer\Rar\UnixWriter;

/**
 * Class RarDetector.
 */
class RarDetector extends AbstractDetector
{
    private const EXCLUDE_OPTIONS = [
        'password',
        'compress',
    ];

    public function detectWriter(array $options): AbstractWriter
    {
        if ($this->isSupportNative($options)) {
            return new NativeWriter();
        }

        if ($this->isUnix()) {
            return new UnixWriter(new RarProcess());
        }

        throw new WriterException('Failed detect writer.');
    }

    private function isSupportNative(array $options): bool
    {
        return count(self::EXCLUDE_OPTIONS) === count(array_diff(self::EXCLUDE_OPTIONS, array_keys($options)));
    }
}
