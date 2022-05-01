<?php

namespace Phare\PharePHP;

class Script
{
    public const ENDPOINT = 'https://cdn.pharehq.com/script.js';

    public function build(string $token, string $nonce = null): string
    {
        $endpoint = self::ENDPOINT;

        if ($nonce) {
            $nonce = "nonce=\"$nonce\"";
        }

        return "<script src=\"$endpoint\" data-token=\"$token\" $nonce defer></script>";
    }

    public static function render(
        string $publicKey,
        string $secretKey,
        string $salt,
        string|int $identifier,
        string $nonce = null
    ): string {
        $token = new Token($publicKey, $secretKey, $salt);

        return (new self())->build(
            $token->create($identifier),
            $nonce
        );
    }
}
