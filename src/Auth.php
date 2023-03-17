<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use GuzzleHttp\Client;

class Auth {
    private Client $httpClient;
    private string $clientID;
    private string $clientSecret;
    private string $tenantID;

    public function __construct(string $clientID, string $clientSecret, string $tenantID) {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->tenantID = $tenantID;

        $this->httpClient = new Client();
    }

    public function getApplicationToken(): ?Token {
        $tokenURL = 'https://login.microsoftonline.com/' . $this->tenantID . '/oauth2/v2.0/token';
        $response = $this->httpClient->post($tokenURL, [
            'form_params' => [
                'client_id' => $this->clientID,
                'client_secret' => $this->clientSecret,
                'scope' => 'https://graph.microsoft.com/.default',
                'grant_type' => 'client_credentials',
            ],
        ])->getBody()->getContents();

        $data = json_decode($response);
        if (!$data) {
            return null;
        }

        return Token::fromMSToken($data);
    }

    public function getDelegatedToken() {
    }
}
