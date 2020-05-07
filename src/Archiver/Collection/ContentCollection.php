<?php

namespace Archiver\Collection;

/**
 * Class ContentCollection.
 */
class ContentCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected string $content;

    /**
     * ContentCollection constructor.
     */
    final public function __construct(string $pathTo, string $content)
    {
        $this
            ->setPathTo($pathTo)
            ->setContent($content)
        ;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return ContentCollection
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
