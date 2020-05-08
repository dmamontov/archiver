<?php

namespace Archiver\Writer;

use Archiver\Collection\ContentCollection;
use Archiver\Collection\DirectoryCollection;
use Archiver\Collection\EmptyDirectoryCollection;
use Archiver\Collection\EmptyFileCollection;
use Archiver\Collection\FileCollection;
use Archiver\Collection\OptionsCollection;
use Archiver\Collection\PatternCollection;
use Archiver\Exception\WriterException;
use Archiver\Helper\StringHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

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
     * @var Filesystem
     */
    protected Filesystem $fs;

    /**
     * @var string
     */
    protected string $fileName;

    /**
     * @var OptionsCollection
     */
    protected OptionsCollection $options;

    /**
     * @var bool
     */
    protected bool $isBackSeparator = false;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    public function write(string $fileName, array $collections, OptionsCollection $options): void
    {
        $this->validate($options);

        $this->options = $options;

        if ($this->fs->exists($fileName)) {
            if ($this->options->getForce()) {
                trigger_error(
                    sprintf('Archive %s will be overwritten.', StringHelper::toBaseName($fileName))
                );

                $this->fs->remove($fileName);
            } else {
                throw new WriterException('File already exists.');
            }
        }

        $this
            ->setFileName($fileName)
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

    protected function writeDirectory(DirectoryCollection $collection): void
    {
        $this->writeFinder(
            (new Finder())->in($collection->getPathFrom()),
            $collection->getPathFrom(),
            $collection->getPathTo(),
        );
    }

    protected function writePattern(PatternCollection $collection): void
    {
        $this->writeFinder(
            (new Finder())->in($collection->getPathFrom())->name($collection->getPattern()),
            $collection->getPathFrom(),
            $collection->getPathTo(),
        );
    }

    protected function writeFinder(Finder $finder, string $pathFrom, string $pathTo): void
    {
        foreach ($finder as $element) {
            if ($this->isBackSeparator) {
                $path = str_replace(['\\', $pathFrom], ['/', $pathTo], $element);
            } else {
                $path = str_replace($pathFrom, $pathTo, $element);
            }

            if ($element->isDir()) {
                $this->writeEmptyDirectory(new EmptyDirectoryCollection($path));

                continue;
            }

            $this->writeFile(new FileCollection((string) $element, $path));
        }
    }

    abstract protected function writeContent(ContentCollection $collection): void;

    abstract protected function writeEmptyFile(EmptyFileCollection $collection): void;

    abstract protected function writeFile(FileCollection $collection): void;

    abstract protected function writeEmptyDirectory(EmptyDirectoryCollection $collection): void;

    private function validate(OptionsCollection $options): void
    {
        if (!defined('static::VALIDATOR_CLASS')) {
            return;
        }

        $validator = static::VALIDATOR_CLASS;

        if (!class_exists($validator)) {
            throw new WriterException('Writer validator not found.');
        }

        (new $validator())->validateWriter($options);
    }
}
