<?php

namespace Archiver\Collection;

use Archiver\Validator;

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
            ->setPattern($pattern)
            ->setPathFrom($pathFrom)
            ->setPathTo($pathTo)
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
        Validator::fs($pathFrom, Validator::FS_TYPE_DIRECTORY);

        $this->pathFrom = $pathFrom;

        return $this;
    }
}
