<?php

namespace App\Dto\Auth;

final readonly class LoginResponse
{
    public function __construct(public string $token)
    {

    }
}
