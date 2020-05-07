<?php

namespace Archiver\Collection;

use Archiver\Validator;

/**
 * Class DirectoryCollection.
 */
class DirectoryCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected string $pathFrom;

    /**
     * DirectoryCollection constructor.
     */
    final public function __construct(string $pathFrom, ?string $pathTo = null)
    {
        $this
            ->setPathFrom($pathFrom)
            ->setPathTo($pathTo)
        ;
    }

    public function getPathFrom(): string
    {
        return str_replace('\\', '/', $this->pathFrom);
    }

    /**
     * @return DirectoryCollection
     */
    public function setPathFrom(string $pathFrom): self
    {
        Validator::fs($pathFrom, Validator::FS_TYPE_DIRECTORY);

        $this->pathFrom = $pathFrom;

        if (null === $this->getPathTo()) {
            $this->setPathTo($pathFrom);
        }

        return $this;
    }
}
