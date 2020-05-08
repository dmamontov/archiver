<?php

namespace Archiver\Validator;

use Archiver\Options;

interface ValidatorInterface
{
    public static function validateWriter(Options $options, bool $return = false): bool;
}
