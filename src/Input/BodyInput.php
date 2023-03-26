<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph\Input;

use Microsoft\Graph\Model\ItemBody;
use ValueError;

class BodyInput {
    public const VALID_TYPES = ['text', 'html'];

    public string $content;
    public string $type;

    public function __construct(string $content, string $type = 'text') {
        if (!in_array($type, BodyInput::VALID_TYPES)) {
            throw new ValueError('Invalid type');
        }
        $this->content = $content;
        $this->type = $type;
    }

    public function toMSGraph(): ItemBody {
        return new ItemBody([
            'content' => $this->content,
            'contentType' => $this->type,
        ]);
    }
}
