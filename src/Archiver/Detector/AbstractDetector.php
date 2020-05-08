<?php

namespace Archiver\Detector;

use Archiver\Collection\OptionsCollection;
use Archiver\Writer\AbstractWriter;

/**
 * Class AbstractDetector.
 */
abstract class AbstractDetector
{
    abstract public function detectWriter(OptionsCollection $options): AbstractWriter;
}
