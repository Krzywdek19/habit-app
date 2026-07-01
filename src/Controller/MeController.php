<?php

namespace App\Controller;

use App\Dto\Auth\MeResponse;
use App\Exception\InvalidTokenException;
use App\Repository\AppUserRepository;
use App\Service\JwtTokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MeController extends AbstractController
{
    public function __construct(
        private readonly JwtTokenService $jwtTokenService,
        private readonly AppUserRepository $userRepository,
    ) {
    }

    #[Route('/api/v1/me', name: 'api_me', methods: ['GET'])]
    public function me(Request $request): JsonResponse
    {
        $authorizationHeader = $request->headers->get('Authorization');

        if ($authorizationHeader === null || !str_starts_with($authorizationHeader, 'Bearer ')) {
            throw new InvalidTokenException();
        }

        $jwtToken = substr($authorizationHeader, 7);

        $payload = $this->jwtTokenService->parseAndValidate($jwtToken);

        $user = $this->userRepository->find($payload['sub']);

        if ($user === null) {
            throw new InvalidTokenException();
        }

        return $this->json(MeResponse::fromEntity($user));
    }
}
