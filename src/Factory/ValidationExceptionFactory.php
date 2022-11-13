<?php

namespace App\Factory;

use Symfony\Component\Validator\ConstraintViolationList;
use App\Exception\ValidationException;

class ValidationExceptionFactory
{
    public function make(ConstraintViolationList $errorList): ValidationException
    {
        return new ValidationException($errorList);
    }
}
