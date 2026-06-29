<?php

namespace App\Controller;

use App\Dto\Auth\LoginRequest;
use App\Dto\Auth\LoginResponse;
use App\Dto\Auth\RegisterUserRequest;
use App\Dto\Auth\RegisterUserResponse;
use App\Service\LoginUserService;
use App\Service\RegisterUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    private RegisterUserService $registerUserService;
    private LoginUserService $loginUserService;

    public function __construct(
        RegisterUserService $registerUserService,
        LoginUserService $loginUserService,
    ) {
        $this->registerUserService = $registerUserService;
        $this->loginUserService = $loginUserService;
    }

    #[Route('/api/v1/auth/register', name: 'auth_register', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegisterUserRequest $request) : JsonResponse
    {
        $appUser = $this->registerUserService->register(
            $request->email,
            $request->password
        );

        return $this->json(RegisterUserResponse::fromEntity($appUser), 201);
    }

    #[Route('/api/v1/auth/login', name: 'auth_login', methods: ['POST'])]
    public function login(#[MapRequestPayload] LoginRequest $request): JsonResponse
    {
        $token = $this->loginUserService->login($request->email, $request->password);

        return $this->json(new LoginResponse($token));
    }
}
