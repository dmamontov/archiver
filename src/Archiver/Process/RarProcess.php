<?php

namespace Archiver\Process;

class RarProcess extends AbstractProcess
{
    protected const BINARY_EXEC = [
        self::BINARY_WRITER => 'rar',
        self::BINARY_EXTRACTOR => 'unrar',
    ];

    public function add(string $fileName, string $pathFrom, string $pathTo, array $options = []): void
    {
        $this->run([
            $this->getBinary(self::BINARY_WRITER),
            'a',
            '-ep1',
            array_key_exists('password', $options) ? "-hp{$options['password']}" : '',
            array_key_exists('compression', $options) ? "-m{$options['compression']}" : '',
            '.' !== $pathTo ? "-ap{$pathTo}" : '',
            '--',
            $fileName,
            $pathFrom,
        ]);
    }

    public function comment(string $fileName, array $options): void
    {
        $this->run([
            $this->getBinary(self::BINARY_WRITER),
            'c',
            array_key_exists('password', $options) ? "-p{$options['password']}" : '',
            '--',
            $fileName,
            $options['comment'],
        ]);
    }
}
