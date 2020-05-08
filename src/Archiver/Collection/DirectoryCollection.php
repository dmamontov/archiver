<?php

namespace Archiver\Collection;

use Archiver\Validator\FileSystemValidator;

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
            ->setPathTo($pathTo)
            ->setPathFrom($pathFrom)
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
        FileSystemValidator::isRead($pathFrom, FileSystemValidator::TYPE_DIRECTORY);

        $this->pathFrom = $pathFrom;

        if (empty($this->getPathTo())) {
            $this->setPathTo(pathinfo($pathFrom, PATHINFO_BASENAME));
        }

        return $this;
    }
}
