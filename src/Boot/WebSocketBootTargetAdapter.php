<?php

declare(strict_types=1);

namespace Componenta\App\WebSocket\Boot;

use Componenta\App\AppInterface;
use Componenta\App\Boot\BootTargetAdapterInterface;
use Componenta\App\Scope;
use Componenta\Scope\ScopeInterface;
use Componenta\App\WebSocket\App;
use Componenta\App\WebSocket\Boot\Target\WebSocketBootTarget;
use LogicException;

final readonly class WebSocketBootTargetAdapter implements BootTargetAdapterInterface
{
    public function supports(ScopeInterface $scope): bool
    {
        return $scope->matches(Scope::WEBSOCKET);
    }

    public function create(AppInterface $app, ScopeInterface $scope): object
    {
        if (!$app instanceof App) {
            throw new LogicException(sprintf(
                'Scope "%s" expects app %s, %s given.',
                $scope->name,
                App::class,
                $app::class,
            ));
        }

        return new WebSocketBootTarget($app);
    }
}
