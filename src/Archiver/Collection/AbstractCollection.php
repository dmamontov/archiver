<?php

namespace Archiver\Collection;

/**
 * Class AbstractCollection.
 */
abstract class AbstractCollection
{
    /**
     * @var null|string
     */
    protected ?string $pathTo;

    final public function getPathTo(): ?string
    {
        return $this->pathTo;
    }

    /**
     * @return $this
     */
    final public function setPathTo(?string $pathTo): self
    {
        $this->pathTo = str_replace('\\', '/', $pathTo);

        return $this;
    }
}
