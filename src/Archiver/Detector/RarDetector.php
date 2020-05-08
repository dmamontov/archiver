<?php

namespace Archiver\Detector;

use Archiver\Collection\OptionsCollection;
use Archiver\Exception\WriterException;
use Archiver\Process\RarProcess;
use Archiver\Validator\Rar\BinaryRarValidator;
use Archiver\Validator\Rar\NativeRarValidator;
use Archiver\Writer\AbstractWriter;
use Archiver\Writer\Rar\BinaryRarWriter;
use Archiver\Writer\Rar\NativeRarWriter;

/**
 * Class RarDetector.
 */
class RarDetector extends AbstractDetector
{
    public function detectWriter(OptionsCollection $options): AbstractWriter
    {
        if (NativeRarValidator::validateWriter($options, true)) {
            return new NativeRarWriter();
        }

        if (BinaryRarValidator::validateWriter($options, true)) {
            return new BinaryRarWriter(new RarProcess());
        }

        throw new WriterException('Failed detect writer.');
    }
}
