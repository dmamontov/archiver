<?php

namespace Archiver\Command\Rar;

/**
 * Class AddRarCommand.
 */
class AddRarCommand extends AbstractRarCommand
{
    /**
     * @var array
     */
    protected array $command = ['a', '-ep1'];

    /**
     * @return AddRarCommand
     */
    public function compressed(?int $compression): self
    {
        if (null !== $compression) {
            $this->command[] = "-m{$compression}";
        }

        return $this;
    }

    /**
     * @return AddRarCommand
     */
    public function replacePath(?string $path): self
    {
        if (!empty($path) && !in_array($path, ['.', '..'])) {
            $this->command[] = "-ap{$path}";
        }

        return $this;
    }

    /**
     * @return AddRarCommand
     */
    public function from(string $fromPath): self
    {
        $this->command[] = $fromPath;

        return $this;
    }
}
