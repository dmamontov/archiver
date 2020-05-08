<?php

namespace Archiver\Collection;

use Archiver\Validator\FileSystemValidator;

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
            ->setPathTo($pathTo)
            ->setPathFrom($pathFrom)
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
        FileSystemValidator::isRead($pathFrom, FileSystemValidator::TYPE_FILE);

        $this->pathFrom = str_replace('\\', '/', $pathFrom);

        if (empty($this->getPathTo())) {
            $this->setPathTo(dirname($pathFrom));
        }

        return $this;
    }
}
