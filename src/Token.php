<?php

namespace Phare\PharePHP;

use Firebase\JWT\JWT;

class Token
{
    public const ALGORITHM = 'HS256';

    public const AUDIENCE = 'https://phare.app';

    public const EXPIRATION = 300;

    public const LEEWAY = 10;

    public function __construct(
        private string $publicKey,
        private string $secretKey,
        private string $salt,
        private int $expiration = self::EXPIRATION,
        private int $leeway = self::LEEWAY
    ) {
    }

    public function create(string|int $identifier): string
    {
        $now = time();

        $payload = [
            'aud' => self::AUDIENCE,
            'sub' => hash_hmac('sha256', (string)$identifier, $this->salt),
            'exp' => $now + $this->expiration,
            'iat' => $now,
            'nbf' => $now,
        ];

        JWT::$leeway = $this->leeway;

        return JWT::encode($payload, $this->secretKey, self::ALGORITHM, $this->publicKey);
    }
}
