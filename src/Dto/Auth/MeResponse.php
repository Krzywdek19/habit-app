<?php

namespace App\Dto\Auth;

use App\Entity\AppUser;

class MeResponse
{
    public function __construct(string $id, string $email, array $roles)
    {

    }

    public static function fromEntity(AppUser $user): self
    {
        return new self($user->getId(),$user->getEmail(), $user->getRoles());
    }
}
