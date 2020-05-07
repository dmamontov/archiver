<?php

namespace Archiver\Writer;

use Archiver\Collection\ContentCollection;
use Archiver\Collection\DirectoryCollection;
use Archiver\Collection\EmptyDirectoryCollection;
use Archiver\Collection\EmptyFileCollection;
use Archiver\Collection\FileCollection;
use Archiver\Collection\PatternCollection;
use Archiver\Exception\WriterException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Class AbstractWriter.
 */
abstract class AbstractWriter
{
    /**
     * @var array
     */
    protected array $tree = [];

    /**
     * @var string
     */
    protected string $fileName;

    /**
     * @var array
     */
    protected array $options = [];

    public function write(string $fileName, array $collections, array $options = []): void
    {
        if (file_exists($fileName)) {
            if (array_key_exists('force', $options) && $options['force']) {
                @unlink($fileName);
            } else {
                throw new WriterException('File already exists.');
            }
        }

        $this
            ->setFileName($fileName)
            ->setOptions($options)
            ->before($collections)
        ;

        foreach ($collections as $collection) {
            switch (get_class($collection)) {
                case ContentCollection::class:
                    $this->writeContent($collection);

                    break;
                case EmptyFileCollection::class:
                    $this->writeEmptyFile($collection);

                    break;
                case FileCollection::class:
                    $this->writeFile($collection);

                    break;
                case EmptyDirectoryCollection::class:
                    $this->writeEmptyDirectory($collection);

                    break;
                case DirectoryCollection::class:
                    $this->writeDirectory($collection);

                    break;
                case PatternCollection::class:
                    $this->writePattern($collection);

                    break;
            }
        }

        $this->after();
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return AbstractWriter
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return AbstractWriter
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return AbstractWriter
     */
    protected function before(array $collections): self
    {
        return $this;
    }

    /**
     * @return AbstractWriter
     */
    protected function after(): self
    {
        return $this;
    }

    protected function buildIterator(string $path, string $regexp = ''): iterable
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        if (!empty($regexp)) {
            $iterator = new RegexIterator($iterator, $regexp);
        }

        return $iterator;
    }

    protected function writeDirectory(DirectoryCollection $collection): void
    {
        $this->writeIterator(
            $this->buildIterator($collection->getPathFrom()),
            $collection->getPathFrom(),
            $collection->getPathTo()
        );
    }

    protected function writePattern(PatternCollection $collection): void
    {
        $this->writeIterator(
            $this->buildIterator($collection->getPathFrom(), $collection->getPattern()),
            $collection->getPathFrom(),
            $collection->getPathTo()
        );
    }

    abstract protected function writeContent(ContentCollection $collection): void;

    abstract protected function writeEmptyFile(EmptyFileCollection $collection): void;

    abstract protected function writeFile(FileCollection $collection): void;

    abstract protected function writeEmptyDirectory(EmptyDirectoryCollection $collection): void;

    abstract protected function writeIterator(iterable $iterator, string $pathFrom, string $pathTo): void;
}
