<?php

namespace Archiver\Validator\Rar;

use Archiver\Collection\OptionsCollection;
use Archiver\Exception\ProcessException;
use Archiver\Exception\RarException;
use Archiver\Process\RarProcess;
use Archiver\Validator\ValidatorInterface;
use Tivie\OS\Detector as OsDetector;

/**
 * Class BinaryRarValidator.
 */
class BinaryRarValidator implements ValidatorInterface
{
    public static function validateWriter(OptionsCollection $options, bool $return = false): bool
    {
        $result = (new OsDetector())->isUnixLike();

        if ($result) {
            try {
                new RarProcess();
            } catch (ProcessException $e) {
                $result = false;
            }
        }

        if (!$result && !$return) {
            throw new RarException('Binary writer does not support this configuration');
        }

        return $result;
    }
}
