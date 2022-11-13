<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationList;
use Exception;

class ValidationException extends Exception
{
    protected ConstraintViolationList $messageList;

    protected $errorMsg = 'Validation has failed';

    protected static $statusCode = 422;

    public function __construct(
        ConstraintViolationList $messageList
    ) {
        $this->messageList = $messageList;
    }

    public function getMessages()
    {
        $messages = [];
        $violations = $this->messageList->getIterator();
    
        foreach ($violations as $violation) {
            $param = $violation->getPropertyPath();
            $messages[$param][] = $violation->getMessage();
        }
        return $messages;
    }
}
