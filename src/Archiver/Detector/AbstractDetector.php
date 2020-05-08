<?php

namespace Archiver\Detector;

use Archiver\Options;
use Archiver\Writer\AbstractWriter;
use Tivie\OS\Detector as OsDetector;

/**
 * Class AbstractDetector.
 */
abstract class AbstractDetector
{
    /**
     * @var OsDetector
     */
    protected OsDetector $os;

    public function __construct()
    {
        $this->os = new OsDetector();
    }

    abstract public function detectWriter(Options $options): AbstractWriter;
}
