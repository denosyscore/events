<?php

declare(strict_types=1);

namespace CFXP\Core\Events;

use Psr\Http\Message\ResponseInterface;

class ResponseReady
{
    public function __construct(
        public readonly ResponseInterface $response
    ) {}
}
