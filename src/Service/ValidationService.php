<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use App\Factory\ValidationExceptionFactory;

class ValidationService
{
    public function __construct(
        protected ValidatorInterface $validator,
        protected ValidationExceptionFactory $validationExceptionFactory
    ) { }

    public function validateEntity($entity)
    {
        $errorList = $this->validator->validate($entity);

        if (count($errorList) > 0) {
            $validationException = $this->validationExceptionFactory->make($errorList);
            throw $validationException;
        }
        return true;
    }

    protected function violationsToArray(ConstraintViolationListInterface $violations)
    {
        $messages = [];
    
        foreach ($violations as $constraint) {
            $prop = $constraint->getPropertyPath();
            $messages[$prop][] = $constraint->getMessage();
        }
    
        return $messages;
    }
}
