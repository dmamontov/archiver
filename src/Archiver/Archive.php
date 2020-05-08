<?php

namespace Archiver;

use Archiver\Action\CreateAction;
use Archiver\Detector\AbstractDetector;
use Archiver\Writer\AbstractWriter;

/**
 * Class AbstractWriter.
 */
abstract class Archive
{
    /**
     * @var AbstractDetector
     */
    protected AbstractDetector $detector;

    protected function __construct()
    {
    }

    public static function create(?AbstractWriter $writer = null): CreateAction
    {
        return new CreateAction((new static())->detector, $writer);
    }
}
