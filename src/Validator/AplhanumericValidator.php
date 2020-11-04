<?php

namespace App\Validator;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AplhanumericValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Aplhanumeric){
            throw new UnexpectedTypeException($constraint, Aplhanumeric::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }


    }
}
