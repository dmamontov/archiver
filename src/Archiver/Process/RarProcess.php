<?php

namespace Archiver\Process;

use Archiver\Command\AbstractCommand;

class RarProcess extends AbstractProcess
{
    protected const BINARY_EXEC = [
        self::BINARY_WRITER => 'rar',
        self::BINARY_EXTRACTOR => 'unrar',
    ];

    public function run(AbstractCommand $command): void
    {
        $this->exec(
            $command->binary($this->getBinary(self::BINARY_WRITER))
        );
    }
}
