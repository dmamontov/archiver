<?php

namespace Archiver\Collection;

/**
 * Class EmptyDirectoryCollection.
 */
class EmptyDirectoryCollection extends AbstractCollection
{
    /**
     * EmptyDirectoryCollection constructor.
     */
    final public function __construct(string $pathTo)
    {
        $this->setPathTo($pathTo);
    }
}
