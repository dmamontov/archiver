<?php

namespace Archiver\Process;

use Archiver\Exception\ProcessException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process as SymfonyProcess;

abstract class AbstractProcess
{
    protected const BINARY_WRITER = 1;
    protected const BINARY_EXTRACTOR = 2;

    protected const BINARY_EXEC = [];

    /**
     * @var array
     */
    protected array $binary = [];

    /**
     * RarProcess constructor.
     */
    public function __construct()
    {
        $this->setBinary($this->detectBinary(static::BINARY_EXEC));
    }

    public function which(string $binary): array
    {
        $process = new SymfonyProcess(['which', $binary]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return array_filter(
            explode("\n", $process->getOutput()),
            static function ($line) {
                return !empty(trim($line));
            }
        );
    }

    public function getBinary(string $key): string
    {
        if (!array_key_exists($key, $this->binary)) {
            throw new ProcessException("Binary for {$key} not found.");
        }

        return $this->binary[$key];
    }

    /**
     * @return $this
     */
    public function setBinary(array $binary = []): self
    {
        $this->binary = $binary;

        return $this;
    }

    public function detectBinary(array $binaries): array
    {
        $detected = [];

        foreach ($binaries as $code => $binary) {
            $finds = $this->which($binary);

            if (!count($finds)) {
                throw new ProcessException("Binary {$binary} not found.");
            }

            $detected[$code] = trim($finds[0]);
        }

        return $detected;
    }

    abstract public function add(string $fileName, string $pathFrom, string $pathTo, array $options = []): void;

    protected function run(array $command): void
    {
        $process = new SymfonyProcess($command);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
