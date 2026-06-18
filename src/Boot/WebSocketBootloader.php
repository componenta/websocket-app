<?php

declare(strict_types=1);

namespace Componenta\App\WebSocket\Boot;

use Componenta\App\Boot\BootContext;
use Componenta\App\Boot\BootloaderInterface;
use Componenta\App\Boot\ScopedBootloaderSupport;
use Componenta\App\Scope;
use Componenta\App\WebSocket\Boot\Target\WebSocketBootTargetInterface;
use Componenta\Stdlib\PathResolverInterface;
use Componenta\Scope\Scopes;

final readonly class WebSocketBootloader implements BootloaderInterface
{
    use ScopedBootloaderSupport;

    private const string CONFIG_FILE = 'config/websocket.php';

    public Scopes $scopes;

    public function __construct(
        private PathResolverInterface $paths,
    ) {
        $this->scopes = Scopes::of(Scope::WEBSOCKET);
    }

    public function boot(BootContext $context): void
    {
        $file = $this->paths->resolve(self::CONFIG_FILE);

        if (!is_file($file)) {
            return;
        }

        $app = $context->target(WebSocketBootTargetInterface::class);

        require $file;
    }

}
