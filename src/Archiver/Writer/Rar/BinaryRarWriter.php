<?php

namespace Archiver\Writer\Rar;

use Archiver\Collection\ContentCollection;
use Archiver\Collection\EmptyDirectoryCollection;
use Archiver\Collection\EmptyFileCollection;
use Archiver\Collection\FileCollection;
use Archiver\Command\Rar\AddRarCommand;
use Archiver\Command\Rar\CommentRarCommand;
use Archiver\Writer\AbstractBinaryWriter;

/**
 * Class UnixWriter.
 */
class BinaryRarWriter extends AbstractBinaryWriter
{
    /**
     * @return BinaryRarWriter
     */
    protected function after(): self
    {
        if ($comment = $this->options->getComment()) {
            $tempFile = $this->tmpPath(uniqid('', true));

            $this->fs->appendToFile($tempFile, $comment);

            $this->getProcess()->run(
                (new CommentRarCommand())
                    ->encrypt($this->options->getPassword())
                    ->fileName($this->getFileName())
                    ->commentFile($tempFile)
            );

            $this->fs->remove($tempFile);
        }

        return $this;
    }

    protected function writeContent(ContentCollection $collection): void
    {
        $pathFrom = $this->tmpPath($collection->getPathTo());

        $this->fs->appendToFile($pathFrom, $collection->getContent());

        $this->writeElement($pathFrom, $collection->getPathTo(), true);
    }

    protected function writeEmptyFile(EmptyFileCollection $collection): void
    {
        $pathFrom = $this->tmpPath($collection->getPathTo());

        $this->fs->touch($pathFrom);

        $this->writeElement($pathFrom, $collection->getPathTo(), true);
    }

    protected function writeFile(FileCollection $collection): void
    {
        $this->writeElement($collection->getPathFrom(), $collection->getPathTo());
    }

    protected function writeEmptyDirectory(EmptyDirectoryCollection $collection): void
    {
        $pathFrom = $this->tmpPath($collection->getPathTo());

        $this->fs->mkdir($pathFrom);

        $this->writeElement($pathFrom, $collection->getPathTo(), true);
    }

    /**
     * @return BinaryRarWriter
     */
    private function writeElement(string $pathFrom, string $pathTo, bool $tempExists = false): self
    {
        if (in_array($pathTo, $this->tree, true)) {
            return $this;
        }

        if (pathinfo($pathFrom, PATHINFO_BASENAME) !== pathinfo($pathTo, PATHINFO_BASENAME)) {
            $tempPath = $this->tmpPath($pathTo);

            $this->fs->copy($pathFrom, $tempPath);

            $pathFrom = $tempPath;
            $tempExists = true;
        }

        $this->getProcess()->run(
            (new AddRarCommand())
                ->encrypt($this->options->getPassword(), true)
                ->compressed($this->options->getCompression())
                ->replacePath(dirname(trim($pathTo, '/')))
                ->fileName($this->getFileName())
                ->from($pathFrom)
        );

        if ($tempExists) {
            $this->fs->remove($pathFrom);
        }

        $this->tree[] = $pathTo;

        return $this;
    }

    private function tmpPath(string $path): string
    {
        return sys_get_temp_dir().'/'.pathinfo($path, PATHINFO_BASENAME);
    }
}
