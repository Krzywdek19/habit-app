<?php

namespace App\Service;

use App\Exception\InvalidCredentialsException;
use App\Repository\AppUserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginUserService {
    private AppUserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private JwtTokenService $jwtTokenService;

    public function __construct(AppUserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, JwtTokenService $jwtTokenService)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->jwtTokenService = $jwtTokenService;
    }

    public function login(string $email, string $plainPassword): string
    {
        $user = $this->userRepository->findByEmail($email);

        if($user === null) {
            throw new InvalidCredentialsException();
        }

        $isPasswordMatch = $this->passwordHasher->isPasswordValid($user, $plainPassword);

        if(!$isPasswordMatch) {
            throw new InvalidCredentialsException();
        }

        return $this->jwtTokenService->generate($user);
    }
}
