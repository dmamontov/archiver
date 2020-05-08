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
use Archiver\Options;
use Archiver\Validator\FileSystemValidator;
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
     * @var AbstractWriter
     */
    protected AbstractWriter $writer;

    /**
     * @var array
     */
    private array $collection = [];

    /**
     * @var Options
     */
    private Options $options;

    /**
     * CreateAction constructor.
     */
    public function __construct(AbstractDetector $detector, ?AbstractWriter $writer)
    {
        $this->options = new Options();

        $this->setDetector($detector);

        if (null !== $writer) {
            $this->setWriter($writer);
        }
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
        $this->options->setPassword(trim($password));

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function compress(int $compression): self
    {
        $this->options->setCompression($compression);

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function comment(string $comment): self
    {
        $this->options->setComment(trim($comment));

        return $this;
    }

    /**
     * @return CreateAction
     */
    public function force(): self
    {
        $this->options->setForce(true);

        return $this;
    }

    public function write(string $fileName): void
    {
        FileSystemValidator::isWrite(dirname($fileName), FileSystemValidator::TYPE_DIRECTORY);

        if (null === $this->getWriter()) {
            throw new WriterException('Writer cannot be empty.');
        }

        $this->getWriter()->write($fileName, $this->collection, $this->options);
    }

    public function getWriter(): AbstractWriter
    {
        return $this->writer ?? $this->detector->detectWriter($this->options);
    }

    private function setDetector(AbstractDetector $detector): CreateAction
    {
        $this->detector = $detector;

        return $this;
    }

    /**
     * @return CreateAction
     */
    private function setWriter(AbstractWriter $writer): self
    {
        $this->writer = $writer;

        return $this;
    }
}
