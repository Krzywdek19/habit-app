<?php

namespace App\Dto\Auth;

use App\Entity\AppUser;

final readonly class RegisterUserResponse
{
    public function __construct(
        public string $id,
        public string $email
    )
    {
    }

    public static function fromEntity(AppUser $user) : self
    {
        return new self(
            id: (string) $user->getId(),
            email: $user->getEmail()
        );
    }
}
