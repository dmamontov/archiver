<?php

namespace Archiver;

use Archiver\Action\CreateAction;
use Archiver\Detector\AbstractDetector;

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

    public static function create(): CreateAction
    {
        return new CreateAction((new static())->detector);
    }
}
