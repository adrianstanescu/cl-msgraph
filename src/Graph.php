<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use Microsoft\Graph\Graph as MSGraph;

final class Graph extends MSGraph {
    private static string $clientID = '';
    private static string $clientSecret = '';
    private static string $tenantID = '';

    private static ?Graph $instance = null;

    private Auth $auth;
    private Token $token;

    public function __construct() {
        parent::__construct();

        $this->auth = new Auth(Graph::$clientID, Graph::$clientSecret, Graph::$tenantID);

        // TODO: refresh token?
        $this->token = $this->auth->getApplicationToken();
        $this->setAccessToken($this->token->getAccessToken());
    }

    public function checkToken() {
        if ($this->token->isExpired()) {
            $this->token = $this->auth->getApplicationToken();
        }

        $this->setAccessToken($this->token->getAccessToken());
    }

    public static function configure(string $clientID, string $clientSecret, string $tenantID, ?int $pageSize = null) {
        Graph::$clientID = $clientID;
        Graph::$clientSecret = $clientSecret;
        Graph::$tenantID = $tenantID;
        if ($pageSize !== null) {
            Collection::setPageSize($pageSize);
        }
    }

    public static function instance(): Graph {
        if (Graph::$instance === null) {
            Graph::$instance = new Graph();
        }

        Graph::$instance->checkToken();

        return Graph::$instance;
    }
}
