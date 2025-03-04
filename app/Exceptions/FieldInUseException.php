<?php

namespace App\Exceptions;

use Exception;

class FieldInUseException extends Exception
{
    public function __construct($message = "Cannot delete field as it is in use by subscribers")
    {
        parent::__construct($message);
    }
}
