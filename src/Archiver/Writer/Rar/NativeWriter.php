<?php

namespace Archiver\Writer\Rar;

use Archiver\Collection\ContentCollection;
use Archiver\Collection\EmptyDirectoryCollection;
use Archiver\Collection\EmptyFileCollection;
use Archiver\Collection\FileCollection;
use Archiver\Exception\RarException;
use Archiver\Writer\AbstractWriter;
use SplFileObject;

class NativeWriter extends AbstractWriter
{
    /**
     * @var SplFileObject
     */
    private SplFileObject $file;

    /**
     * @return NativeWriter
     */
    protected function before(array $collections): self
    {
        $this->file = new SplFileObject($this->getFileName(), 'w+b');

        $this
            ->writeHeader(0x72, 0x1a21)
            ->writeHeader(0x73, 0x0000, [[0, 2], [0, 4]])
        ;

        return $this;
    }

    protected function writeContent(ContentCollection $collection): void
    {
        $pathTo = trim($collection->getPathTo(), '/');

        if (false !== mb_stripos($pathTo, '/')) {
            $this->writeEmptyDirectory(new EmptyDirectoryCollection(dirname($pathTo)));
        }

        $content = $collection->getContent();
        $pathTo = str_replace('/', '\\', $pathTo);

        $this->writeHeader(
            0x74,
            $this->setBits([15]),
            [
                [mb_strlen($content), 4],
                [mb_strlen($content), 4],
                [0, 1],
                [crc32($content), 4],
                [$this->getDateTime(), 4],
                [20, 1],
                [0x30, 1],
                [mb_strlen($pathTo), 2],
                [0x20, 4],
                $pathTo,
            ]
        );

        $this->file->fwrite($content);
    }

    protected function writeEmptyFile(EmptyFileCollection $collection): void
    {
        $this->writeContent(new ContentCollection($collection->getPathTo(), ''));
    }

    protected function writeFile(FileCollection $collection): void
    {
        $file = new SplFileObject($collection->getPathFrom(), 'r');

        $content = '';

        $size = $file->getSize();
        if ($size > 0) {
            $content = $file->fread($size);
        }

        $this->writeContent(new ContentCollection($collection->getPathTo(), $content));
    }

    protected function writeEmptyDirectory(EmptyDirectoryCollection $collection): void
    {
        $pathTo = trim($collection->getPathTo(), '/');

        $pathParts = explode('/', $pathTo);
        $newPath = '';
        foreach ($pathParts as $part) {
            $newPath .= $part;

            if (in_array($newPath, $this->tree, true)) {
                $newPath .= '\\';

                continue;
            }

            $this->writeHeader(
                0x74,
                $this->setBits([5, 6, 7, 15]),
                [
                    [0, 4],
                    [0, 4],
                    [0, 1],
                    [0, 4],
                    [$this->getDateTime(), 4],
                    [20, 1],
                    [0x30, 1],
                    [mb_strlen($newPath), 2],
                    [0x10, 4],
                    $newPath,
                ]
            );

            $newPath .= '\\';
        }
    }

    protected function writeIterator(iterable $iterator, string $pathFrom, string $pathTo): void
    {
        foreach ($iterator as $element) {
            $path = str_replace(['\\', $pathFrom], ['/', $pathTo], $element);

            if ($element->isDir()) {
                $this->writeEmptyDirectory(new EmptyDirectoryCollection($path));

                continue;
            }

            $this->writeFile(new FileCollection((string) $element, $path));
        }
    }

    /**
     * @return NativeWriter
     */
    private function writeHeader(int $type, int $flag, array $data = []): self
    {
        if (!in_array($type, [0x72, 0x73], true)) {
            if (0x74 !== $type) {
                throw new RarException('Invalid header type.');
            }

            if (is_string(end($data)) && in_array(end($data), $this->tree, true)) {
                return $this;
            }
        }

        if (is_string(end($data))) {
            $this->tree[] = end($data);
        }

        $size = 7;
        foreach ($data as $key => $value) {
            $size += is_array($value) ? $value[1] : mb_strlen($value);
        }

        $bytes = array_merge([$type, [$flag, 2], [$size, 2]], $data);

        $header = '';

        foreach ($bytes as $byte) {
            $header .= is_array($byte)
                ? $this->getBytes((string) $byte[0], $byte[1])
                : $this->getBytes((string) $byte)
            ;
        }

        $header = (0x72 === $type ? 'Ra' : $this->getCRC($header)).$header;

        $this->file->fwrite($header);

        return $this;
    }

    private function getBytes(string $data, int $byte = 0, int $count = 0): string
    {
        if (0 < $count && $byte) {
            return hexdec(bin2hex($this->strRev(mb_substr($data, $byte, $count))));
        }

        if (!is_numeric($data)) {
            return $data;
        }

        if (!$byte) {
            $byte = 1;
        }

        $result = '';

        $data = sprintf('%0'.($byte * 2).'x', $data);
        for ($i = 0, $max = mb_strlen($data); $i < $max; $i += 2) {
            $result = chr(hexdec(substr($data, $i, 2))).$result;
        }

        return $result;
    }

    private function setBits(array $bits): int
    {
        $result = 0;

        foreach ($bits as $bit) {
            $result |= 1 << $bit;
        }

        return $result;
    }

    private function getCRC(string $string): string
    {
        $crc = crc32($string);

        return chr($crc & 0xFF).chr(($crc >> 8) & 0xFF);
    }

    private function getDateTime(?int $time = null): int
    {
        if (null === $time) {
            $time = time();
        }

        $dateTime = getdate($time);

        return $dateTime['seconds']
            | ($dateTime['minutes'] << 5)
            | ($dateTime['hours'] << 11)
            | ($dateTime['mday'] << 16)
            | ($dateTime['mon'] << 21)
            | (($dateTime['year'] - 1980) << 25)
        ;
    }

    private function strRev(string $string): string
    {
        $result = '';
        for ($i = mb_strlen($string); $i >= 0; --$i) {
            $result .= mb_substr($string, $i, 1);
        }

        return $result;
    }
}
