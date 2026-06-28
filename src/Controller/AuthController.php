<?php

namespace App\Controller;

use App\Dto\Auth\RegisterUserRequest;
use App\Dto\Auth\RegisterUserResponse;
use App\Service\RegisterUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    private RegisterUserService $registerUserService;

    public function __construct(RegisterUserService $registerUserService)
    {
        $this->registerUserService = $registerUserService;
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
}
