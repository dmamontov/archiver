<?php

namespace Archiver\Collection;

/**
 * Class EmptyDirectoryCollection.
 */
class EmptyFileCollection extends AbstractCollection
{
    /**
     * EmptyFileCollection constructor.
     */
    final public function __construct(string $pathTo)
    {
        $this->setPathTo($pathTo);
    }
}
