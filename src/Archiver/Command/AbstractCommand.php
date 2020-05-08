<?php

namespace Archiver\Command;

/**
 * Class AbstractCommand.
 */
abstract class AbstractCommand
{
    /**
     * @var array
     */
    protected array $command = [];

    /**
     * @return $this
     */
    public function binary(string $binary): self
    {
        array_unshift($this->command, $binary);

        return $this;
    }

    public function toArray(): array
    {
        return $this->command;
    }
}
