<?php

namespace Archiver\Detector;

use Archiver\Options;
use Archiver\Writer\AbstractWriter;

/**
 * Class AbstractDetector.
 */
abstract class AbstractDetector
{
    abstract public function detectWriter(Options $options): AbstractWriter;
}
