<?php

namespace Archiver\Command\Rar;

/**
 * Class CommentRarCommand.
 */
class CommentRarCommand extends AbstractRarCommand
{
    /**
     * @var array
     */
    protected array $command = ['c'];

    /**
     * @return CommentRarCommand
     */
    public function commentFile(string $file): self
    {
        $this->command[] = "-z{$file}";

        return $this;
    }
}
