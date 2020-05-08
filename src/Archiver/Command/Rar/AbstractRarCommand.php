<?php

namespace Archiver\Command\Rar;

use Archiver\Command\AbstractCommand;
use Archiver\Exception\PathException;

/**
 * Class CommentCommand.
 */
abstract class AbstractRarCommand extends AbstractCommand
{
    protected const SEPARATOR = '--';

    /**
     * @return AbstractRarCommand
     */
    public function encrypt(?string $password, bool $isWrite = false): self
    {
        if (!empty($password)) {
            $this->command[] = '-'.($isWrite ? 'hp' : 'p').$password;
        }

        return $this;
    }

    /**
     * @return AbstractRarCommand
     */
    public function separate(): self
    {
        $this->command[] = self::SEPARATOR;

        return $this;
    }

    /**
     * @return AbstractRarCommand
     */
    public function fileName(string $fileName): self
    {
        if (empty($fileName)) {
            throw new PathException('The file name cannot be empty');
        }

        if (self::SEPARATOR !== end($this->command)) {
            $this->separate();
        }

        $this->command[] = $fileName;

        return $this;
    }
}
