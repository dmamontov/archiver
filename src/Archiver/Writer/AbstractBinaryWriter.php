<?php

namespace Archiver\Writer;

use Archiver\Process\AbstractProcess;

/**
 * Class AbstractBinaryWriter.
 */
abstract class AbstractBinaryWriter extends AbstractWriter
{
    /**
     * @var AbstractProcess
     */
    protected AbstractProcess $process;

    /**
     * AbstractBinaryWriter constructor.
     */
    public function __construct(AbstractProcess $process)
    {
        parent::__construct();

        $this->setProcess($process);
    }

    public function getProcess(): AbstractProcess
    {
        return $this->process;
    }

    /**
     * @return AbstractBinaryWriter
     */
    public function setProcess(AbstractProcess $process): self
    {
        $this->process = $process;

        return $this;
    }
}
