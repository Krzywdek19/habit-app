<?php

namespace App\Exception;

use RuntimeException;

class InvalidTokenException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Invalid or expired token");
    }
}
