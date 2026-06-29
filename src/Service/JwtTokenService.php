<?php

namespace App\Service;

use App\Entity\AppUser;

readonly class JwtTokenService
{
    public function __construct(
        private string $jwtSecret,
        private int    $jwtTtlSeconds
    )
    {

    }
    public function generate(AppUser $user): string
    {
        $now = time();

        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $payload = [
            'sub' => (string) $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'iat' => $now,
            'exp' => $now + $this->jwtTtlSeconds
            ];

        $encodedHeader = $this->base64UrlEncode(json_encode($header));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload));

        $data = $encodedHeader . '.' . $encodedPayload;

        $signature = hash_hmac('sha256', $data, $this -> jwtSecret, true);
        $encodedSignature = $this->base64UrlEncode($signature);

        return $data . '.' . $encodedSignature;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data),'+/', '-_'), '=');
    }
}
