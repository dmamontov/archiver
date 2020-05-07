<?php

namespace Archiver\Collection;

use Archiver\Validator;

/**
 * Class FileCollection.
 */
class FileCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected string $pathFrom;

    /**
     * FileCollection constructor.
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
        return $this->pathFrom;
    }

    /**
     * @return FileCollection
     */
    public function setPathFrom(string $pathFrom): self
    {
        Validator::fs($pathFrom, Validator::FS_TYPE_FILE);

        $this->pathFrom = str_replace('\\', '/', $pathFrom);

        if (null === $this->getPathTo()) {
            $this->setPathTo($pathFrom);
        }

        return $this;
    }
}
