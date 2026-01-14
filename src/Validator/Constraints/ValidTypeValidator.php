<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidTypeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value !== null && !\in_array($value, ['Security', 'Content'], true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}