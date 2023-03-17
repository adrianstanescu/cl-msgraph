<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use stdClass;
use DateTime;
use DateInterval;
use DateTimeInterface;

class Token {
    const TYPE_APPLICATION = 'Application';

    private string $type;
    private string $accessToken;
    private DateTime $expiresAt;

    public function __construct(string $accessToken, \DateTime $expiresAt, string $type = Token::TYPE_APPLICATION) {
        $this->type = $type;
        $this->accessToken = $accessToken;
        $this->expiresAt = $expiresAt;
    }

    public static function fromMSToken(stdClass $token, $type = Token::TYPE_APPLICATION) {
        if ($token->token_type !== 'Bearer') {
            throw new \Error('Unsupported token_type ' . $token->token_type);
        }
        $now = new DateTime();
        // remove microseconds
        
        return new Token(
            $token->access_token,
            (new DateTime())->add(new DateInterval('PT' . intval($token->expires_in) . 'S')),
            $type,
        );
    }

    public static function parse(string $token) {
        $t = json_decode($token);

        return new Token(
            $t->a,
            DateTime::createFromFormat(DateTimeInterface::ATOM, $t->e),
            $t->t,
        );
    }

    public function stringify(bool $pretty = false): string {
        return json_encode([
            'a' => $this->accessToken,
            'e' => $this->expiresAt->format(DateTimeInterface::ATOM),
            't' => $this->type,
        ], $pretty ? JSON_PRETTY_PRINT : 0);
    }

    public function getAccessToken(): string {
        return $this->accessToken;
    }

    public function isExpired(): bool {
        return $this->expiresAt <= new DateTime();
    }
}
