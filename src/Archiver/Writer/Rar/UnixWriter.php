<?php

namespace Archiver\Writer\Rar;

use Archiver\Collection\ContentCollection;
use Archiver\Collection\EmptyDirectoryCollection;
use Archiver\Collection\EmptyFileCollection;
use Archiver\Collection\FileCollection;
use Archiver\Exception\WriterException;
use Archiver\Writer\AbstractBinaryWriter;

/**
 * Class UnixWriter.
 */
class UnixWriter extends AbstractBinaryWriter
{
    /**
     * @return UnixWriter
     */
    protected function after(): self
    {
        if (array_key_exists('comment', $this->getOptions()) && !empty($this->getOptions()['comment'])) {
            $this->getProcess()->comment(
                $this->getFileName(),
                $this->getOptions(),
            );
        }

        return $this;
    }

    protected function writeContent(ContentCollection $collection): void
    {
        $pathFrom = sys_get_temp_dir().'/'.pathinfo($collection->getPathTo(), PATHINFO_BASENAME);

        file_put_contents($pathFrom, $collection->getContent());

        $this->writeElement($pathFrom, $collection->getPathTo(), true);
    }

    protected function writeEmptyFile(EmptyFileCollection $collection): void
    {
        $pathFrom = sys_get_temp_dir().'/'.pathinfo($collection->getPathTo(), PATHINFO_BASENAME);

        touch($pathFrom);

        $this->writeElement($pathFrom, $collection->getPathTo(), true);
    }

    protected function writeFile(FileCollection $collection): void
    {
        $this->writeElement($collection->getPathFrom(), $collection->getPathTo());
    }

    protected function writeEmptyDirectory(EmptyDirectoryCollection $collection): void
    {
        $parts = explode('/', $collection->getPathTo());

        $pathFrom = sys_get_temp_dir().'/'.end($parts);

        if (!mkdir($pathFrom, 0777, true) && !is_dir($pathFrom)) {
            throw new WriterException(sprintf('Directory "%s" was not created', $pathFrom));
        }

        $this->writeElement($pathFrom, $collection->getPathTo(), true);
    }

    protected function writeIterator(iterable $iterator, string $pathFrom, string $pathTo): void
    {
        foreach ($iterator as $element) {
            $path = str_replace($pathFrom, $pathTo, $element);

            if ($element->isDir()) {
                $this->writeEmptyDirectory(new EmptyDirectoryCollection($path));

                continue;
            }

            $this->writeFile(new FileCollection((string) $element, $path));
        }
    }

    /**
     * @return UnixWriter
     */
    private function writeElement(string $pathFrom, string $pathTo, bool $tempExists = false): self
    {
        if (in_array($pathTo, $this->tree, true)) {
            return $this;
        }

        if (pathinfo($pathFrom, PATHINFO_BASENAME) !== pathinfo($pathTo, PATHINFO_BASENAME)) {
            $tempPath = sys_get_temp_dir().'/'.pathinfo($pathTo, PATHINFO_BASENAME);

            copy($pathFrom, $tempPath);

            $pathFrom = $tempPath;
            $tempExists = true;
        }

        $this->getProcess()->add(
            $this->getFileName(),
            $pathFrom,
            dirname(trim($pathTo, '/')),
            $this->getOptions()
        );

        if ($tempExists) {
            if (is_dir($pathFrom)) {
                rmdir($pathFrom);
            } else {
                unlink($pathFrom);
            }
        }

        $this->tree[] = $pathTo;

        return $this;
    }
}
