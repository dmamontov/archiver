<?php

namespace Archiver\Validator;

use Archiver\Collection\OptionsCollection;

interface ValidatorInterface
{
    public static function validateWriter(OptionsCollection $options, bool $return = false): bool;
}
