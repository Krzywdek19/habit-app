<?php

namespace App\Tests\Unit\Service;

use App\Entity\AppUser;
use App\Exception\InvalidTokenException;
use App\Service\JwtTokenService;
use PHPUnit\Framework\TestCase;

class JwtTokenServiceImplTest extends TestCase
{
    public function testShouldGenerateTokenAndParsePayload(): void
    {
        $service = new JwtTokenService(
            jwtSecret: 'test_secret_123456789',
            jwtTtlSeconds: 3600,
        );

        $user = new AppUser();
        $user->setEmail('test@example.com');
        $user->setRoles(['ROLE_USER']);

        $token = $service->generate($user);

        $payload = $service->parseAndValidate($token);

        $this->assertSame('test@example.com', $payload['email']);
        $this->assertContains('ROLE_USER', $payload['roles']);
        $this->assertArrayHasKey('iat', $payload);
        $this->assertArrayHasKey('exp', $payload);
    }

    public function testShouldThrowExceptionWhenTokenWasModified(): void
    {
        $service = new JwtTokenService(
            jwtSecret: 'test_secret_123456789',
            jwtTtlSeconds: 3600,
        );

        $user = new AppUser();
        $user->setEmail('test@example.com');
        $user->setRoles(['ROLE_USER']);

        $token = $service->generate($user);

        $token = $token . 'abc';

        $this->expectException(InvalidTokenException::class);

        $payload = $service->parseAndValidate($token);
    }

    public function testShouldThrowExceptionWhenTokenIsExpired(): void
    {
        $service = new JwtTokenService(
            jwtSecret: 'test_secret_123456789',
            jwtTtlSeconds: -10,
        );

        $user = new AppUser();
        $user->setEmail('test@example.com');

        $token = $service->generate($user);

        $this->expectException(InvalidTokenException::class);

        $service->parseAndValidate($token);
    }
}
