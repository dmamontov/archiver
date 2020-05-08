<?php

namespace Archiver\Detector;

use Archiver\Exception\WriterException;
use Archiver\Options;
use Archiver\Process\RarProcess;
use Archiver\Rar;
use Archiver\Writer\AbstractWriter;
use Archiver\Writer\Rar\BinaryRarWriter;
use Archiver\Writer\Rar\NativeRarWriter;

/**
 * Class RarDetector.
 */
class RarDetector extends AbstractDetector
{
    public function detectWriter(Options $options): AbstractWriter
    {
        if ($this->isSupportNative($options)) {
            return new NativeRarWriter();
        }

        if ($this->os->isUnixLike()) {
            return new BinaryRarWriter(new RarProcess());
        }

        throw new WriterException('Failed detect writer.');
    }

    public function isSupportNative(Options $options): bool
    {
        return (!$options->isCompression() || Rar::COMPRESSION_STORE === $options->getCompression())
            && !count(array_diff($options->keys(), ['force', 'compression']))
        ;
    }
}
