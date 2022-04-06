<?php

namespace Phare\PharePHP;

class Script
{
    public const ENDPOINT = 'https://cdn.phare.app/script.js';

    public function build(string $token): string
    {
        $endpoint = self::ENDPOINT;

        return "<script src=\"$endpoint\" data-token=\"$token\" defer></script>";
    }

    public static function make(
        string $publicKey,
        string $secretKey,
        string $salt,
        string|int $identifier,
    ): string {
        $token = new Token($publicKey, $secretKey, $salt);

        return (new self())->build(
            $token->create($identifier)
        );
    }
}
