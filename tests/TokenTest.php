<?php

namespace Phare\PharePHP\Tests;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phare\PharePHP\Token;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class TokenTest extends TestCase
{
    public function randomString($length = 10): string
    {
        $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($x, ceil($length / strlen($x)))), 1, $length);
    }

    public function test_it_can_initialize(): void
    {
        $token = new Token(
            $this->randomString(),
            $this->randomString(),
            $this->randomString()
        );

        $this->assertInstanceOf(Token::class, $token);
    }

    public function test_it_can_create_token()
    {
        $private = $this->randomString();
        $expiration = random_int(0, 100);

        $identifier = $this->randomString();

        $jwt = (new Token(
            $this->randomString(),
            $private,
            $this->randomString(),
            $expiration
        ))->create($identifier);

        $decodedJwt = JWT::decode($jwt, new Key($private, Token::ALGORITHM));

        $this->assertIsObject($decodedJwt);

        $this->assertNotEquals($identifier, $decodedJwt->sub);
        $this->assertEquals(Token::AUDIENCE, $decodedJwt->aud);
        $this->assertEquals($decodedJwt->iat, $decodedJwt->nbf);
        $this->assertEquals($decodedJwt->iat + $expiration, $decodedJwt->exp);
    }

    public function test_it_can_create_token_with_integer_identifier()
    {
        $private = $this->randomString();

        $identifier = random_int(0, PHP_INT_MAX);

        $jwt = (new Token(
            $this->randomString(),
            $private,
            $this->randomString()
        ))->create($identifier);

        $decodedJwt = JWT::decode($jwt, new Key($private, Token::ALGORITHM));

        $this->assertIsObject($decodedJwt);

        $this->assertTrue(is_string($decodedJwt->sub));
        $this->assertNotEquals((string)$identifier, $decodedJwt->sub);
    }
}
