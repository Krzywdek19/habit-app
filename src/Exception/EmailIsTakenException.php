<?php

namespace App\Exception;

use RuntimeException;

class EmailIsTakenException extends RuntimeException
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('Email "%s" is already taken', $email));
    }
}

