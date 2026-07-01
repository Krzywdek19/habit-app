<?php

namespace App\Service;

use App\Entity\AppUser;
use App\Exception\InvalidTokenException;

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

    public function parseAndValidate(string $token): array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new InvalidTokenException();
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

        $data = $encodedHeader . '.' . $encodedPayload;

        $expectedSignature = hash_hmac('sha256', $data, $this->jwtSecret, true);
        $expectedEncodedSignature = $this->base64UrlEncode($expectedSignature);

        if (!hash_equals($expectedEncodedSignature, $encodedSignature)) {
            throw new InvalidTokenException();
        }

        $payloadJson = $this->base64UrlDecode($encodedPayload);
        $payload = json_decode($payloadJson, true, 512, JSON_THROW_ON_ERROR);

        if (!isset($payload['exp']) || $payload['exp'] < time()) {
            throw new InvalidTokenException();
        }

        return $payload;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data),'+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_','+/'));
    }
}
