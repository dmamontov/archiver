<?php

namespace Archiver\Collection;

use Archiver\Helper\StringHelper;
use Archiver\Validator\FileSystemValidator;

/**
 * Class PatternCollection.
 */
class PatternCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected string $pattern = '*';

    /**
     * @var string
     */
    protected string $pathFrom;

    /**
     * PatternCollection constructor.
     */
    final public function __construct(string $pattern, string $pathFrom, ?string $pathTo = null)
    {
        $this
            ->setPathTo($pathTo)
            ->setPattern($pattern)
            ->setPathFrom($pathFrom)
        ;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return PatternCollection
     */
    public function setPattern(string $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }

    public function getPathFrom(): string
    {
        return str_replace('\\', '/', $this->pathFrom);
    }

    /**
     * @return PatternCollection
     */
    public function setPathFrom(string $pathFrom): self
    {
        FileSystemValidator::isRead($pathFrom, FileSystemValidator::TYPE_DIRECTORY);

        $this->pathFrom = $pathFrom;

        if (empty($this->getPathTo())) {
            $this->setPathTo(StringHelper::toBaseName($pathFrom));
        }

        return $this;
    }
}
