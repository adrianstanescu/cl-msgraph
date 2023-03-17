<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class AuthTest extends TestCase {
    public function testCanBeCreated(): void {
        $this->assertInstanceOf(
            Auth::class,
            new Auth('', '', '')
        );
    }

    public function testCanGetApplicationToken(): void {
        $auth = new Auth(getenv('AZURE_CLIENT_ID'), getenv('AZURE_CLIENT_SECRET'), getenv('AZURE_TENANT_ID'));
        $token = $auth->getApplicationToken();
        $this->assertInstanceOf(Token::class, $token);
        $this->assertFalse($token->isExpired());
        $this->assertIsString($token->getAccessToken());
        $this->assertIsString($token->stringify());
    }

    public function testTokenExpiration(): void {
        $token = new Token('test', new DateTime());
        $this->assertTrue($token->isExpired());
    }

    public function testTokenSerialization(): void {
        $now = new DateTime();
        $now->setTime(0, 0, 0);
        $token = new Token('test', $now);
        $this->assertEquals($token, Token::parse($token->stringify()));
    }

    // public function testGetApplicationTokenStatic(): void {
    //     Auth::configure([
    //         'clientID' => getenv('AZURE_CLIENT_ID'),
    //         'clientSecret' => getenv('AZURE_CLIENT_SECRET'),
    //         'tenantID' => getenv('AZURE_TENANT_ID'),
    //     ]);
    //     $token = Auth::getApplicationToken();
    //     $this->assertIsString($token);
    // }
}
