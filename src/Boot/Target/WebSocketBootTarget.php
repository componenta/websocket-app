<?php

declare(strict_types=1);

namespace Componenta\App\WebSocket\Boot\Target;

use Componenta\App\WebSocket\App;

final class WebSocketBootTarget implements WebSocketBootTargetInterface
{
    public mixed $application {
        set => $this->app->setApplication($value);
    }

    public function __construct(
        private readonly App $app,
    ) {}
}
