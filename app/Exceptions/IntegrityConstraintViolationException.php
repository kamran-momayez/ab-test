<?php

namespace App\Exceptions;

use Exception;

class IntegrityConstraintViolationException extends Exception
{
    public function __construct($message = 'Integrity constraint violation', $code = 23000, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
