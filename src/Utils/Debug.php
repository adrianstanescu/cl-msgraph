<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph\Utils;

class Debug {
    public static function dump($msg, ...$values): void {
        if (getenv('DEBUG') !== 'yes') {
            return;
        }
        if (is_string($msg)) {
            $message = sprintf($msg, ...$values);
        } else {
            $message = print_r($msg, true);
        }
        error_log($message);
    }
}
