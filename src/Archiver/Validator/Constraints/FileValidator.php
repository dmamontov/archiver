<?php

namespace Archiver\Validator\Constraints;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class ExistsValidator.
 */
class FileValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof File) {
            throw new UnexpectedTypeException($constraint, File::class);
        }

        $fs = new Filesystem();

        if ($constraint->exists && !$fs->exists($value)) {
            $this->context
                ->buildViolation($constraint->messages[File::NOT_EXISTS])
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(File::NOT_EXISTS)
                ->addViolation()
            ;

            return;
        }

        if ($constraint->isDir && !is_dir($value)) {
            $this->context
                ->buildViolation($constraint->messages[File::NOT_DIRECTORY])
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(File::NOT_DIRECTORY)
                ->addViolation()
            ;
        }

        if (!$constraint->isDir && !is_file($value)) {
            $this->context
                ->buildViolation($constraint->messages[File::NOT_FILE])
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(File::NOT_FILE)
                ->addViolation()
            ;
        }

        if ($constraint->readable && !is_readable($value)) {
            $this->context
                ->buildViolation($constraint->messages[File::NOT_READABLE])
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(File::NOT_READABLE)
                ->addViolation()
            ;
        }

        if ($constraint->writable && !is_writable($value)) {
            $this->context
                ->buildViolation($constraint->messages[File::NOT_WRITABLE])
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(File::NOT_WRITABLE)
                ->addViolation()
            ;
        }
    }
}
