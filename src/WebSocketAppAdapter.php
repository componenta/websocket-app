<?php

declare(strict_types=1);

namespace Componenta\App\WebSocket;

use Componenta\App\AppAdapterInterface;
use Componenta\App\AppInterface;
use Componenta\App\Scope;
use Componenta\Config\ContainerValue;
use Componenta\Scope\ScopeInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final readonly class WebSocketAppAdapter implements AppAdapterInterface
{
    public function supports(ScopeInterface $scope): bool
    {
        return $scope->matches(Scope::WEBSOCKET);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createApp(ScopeInterface $scope, ContainerValue $container): AppInterface
    {
        return App::createFromContainer($container);
    }
}
