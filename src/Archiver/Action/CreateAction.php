<?php

namespace Archiver\Action;

use Archiver\Collection\ContentCollection;
use Archiver\Collection\DirectoryCollection;
use Archiver\Collection\EmptyDirectoryCollection;
use Archiver\Collection\EmptyFileCollection;
use Archiver\Collection\FileCollection;
use Archiver\Collection\PatternCollection;
use Archiver\Detector\AbstractDetector;
use Archiver\Exception\WriterException;
use Archiver\Validator;
use Archiver\Writer\AbstractWriter;

/**
 * Class CreateAction.
 */
class CreateAction
{
    /**
     * @var AbstractDetector
     */
    protected AbstractDetector $detector;
    /**
     * @var array
     */
    private array $collection = [];

    /**
     * @var array
     */
    private array $options = [];

    /**
     * CreateAction constructor.
     */
    public function __construct(AbstractDetector $detector)
    {
        $this->setDetector($detector);
    }

    /**
     * @return CreateAction
     */
    public function createFile(string $pathTo): self
    {
        $this->collection[] = new EmptyFileCollection($pathTo);

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function createDirectory(string $pathTo): self
    {
        $this->collection[] = new EmptyDirectoryCollection($pathTo);

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function addFile(string $pathFrom, ?string $pathTo = null): self
    {
        $this->collection[] = new FileCollection($pathFrom, $pathTo);

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function addContent(string $pathTo, string $content): self
    {
        $this->collection[] = new ContentCollection($pathTo, $content);

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function addDirectory(string $pathFrom, ?string $pathTo = null): self
    {
        $this->collection[] = new DirectoryCollection($pathFrom, $pathTo);

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function addPattern(string $pattern, string $pathFrom, ?string $pathTo = null): self
    {
        $this->collection[] = new PatternCollection($pattern, $pathFrom, $pathTo);

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function encrypt(string $password): self
    {
        $this->options['password'] = trim($password);

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function compress(int $compression): self
    {
        $this->options['compression'] = $compression;

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function comment(string $comment): self
    {
        $this->options['comment'] = trim($comment);

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function force(): self
    {
        $this->options['force'] = true;

        return $this;
    }

    public function write(string $fileName): void
    {
        Validator::fs(dirname($fileName), Validator::FS_TYPE_DIRECTORY, true);

        if (null === $this->getWriter()) {
            throw new WriterException('Writer cannot be empty.');
        }

        $this->getWriter()->write($fileName, $this->collection, $this->options);
    }

    public function getWriter(): AbstractWriter
    {
        return $this->detector->detectWriter($this->options);
    }

    public function setDetector(AbstractDetector $detector): CreateAction
    {
        $this->detector = $detector;

        return $this;
    }
}
