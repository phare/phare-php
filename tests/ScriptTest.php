<?php

namespace Phare\PharePHP\Tests;

use DOMDocument;
use DOMXPath;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phare\PharePHP\Script;
use Phare\PharePHP\Token;
use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    public function randomString($length = 10): string
    {
        $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($x, ceil($length / strlen($x)))), 1, $length);
    }

    public function test_it_can_initialize(): void
    {
        $script = new Script();

        $this->assertInstanceOf(Script::class, $script);
    }

    public function test_it_can_build_script(): void
    {
        $token = $this->randomString();

        $script = (new Script())->build($token);

        $document = new DomDocument();

        $document->loadHTML($script);

        $xpath = new DOMXPath($document);

        /** @var \DOMElement $scriptNode */
        $scriptNode = $xpath->query('//script')[0];

        $this->assertEquals($token, $scriptNode->getAttribute('data-token'));
        $this->assertEquals(Script::ENDPOINT, $scriptNode->getAttribute('src'));
        $this->assertTrue($scriptNode->hasAttribute('defer'));
        $this->assertFalse($scriptNode->hasAttribute('nonce'));
    }

    public function test_it_can_build_script_with_nonce(): void
    {
        $token = $this->randomString();
        $nonce = $this->randomString();

        $script = (new Script())->build($token, $nonce);

        $document = new DomDocument();

        $document->loadHTML($script);

        $xpath = new DOMXPath($document);

        /** @var \DOMElement $scriptNode */
        $scriptNode = $xpath->query('//script')[0];

        $this->assertEquals($token, $scriptNode->getAttribute('data-token'));
        $this->assertEquals(Script::ENDPOINT, $scriptNode->getAttribute('src'));
        $this->assertEquals($nonce, $scriptNode->getAttribute('nonce'));
        $this->assertTrue($scriptNode->hasAttribute('defer'));
    }

    public function test_it_can_build_script_with_make_static_function(): void
    {
        $secret = $this->randomString();

        $script = Script::make(
            $this->randomString(),
            $secret,
            $this->randomString(),
            $this->randomString(),
            $this->randomString()
        );

        $document = new DomDocument();

        $document->loadHTML($script);

        $xpath = new DOMXPath($document);

        /** @var \DOMElement $scriptNode */
        $scriptNode = $xpath->query('//script')[0];

        $this->assertEquals(Script::ENDPOINT, $scriptNode->getAttribute('src'));
        $this->assertTrue($scriptNode->hasAttribute('defer'));
        $this->assertTrue($scriptNode->hasAttribute('nonce'));

        $token = $scriptNode->getAttribute('data-token');

        $jwt = JWT::decode($token, new Key($secret, Token::ALGORITHM));

        $this->assertObjectHasAttribute('aud', $jwt);
        $this->assertObjectHasAttribute('sub', $jwt);
        $this->assertObjectHasAttribute('exp', $jwt);
        $this->assertObjectHasAttribute('iat', $jwt);
        $this->assertObjectHasAttribute('nbf', $jwt);
    }
}
