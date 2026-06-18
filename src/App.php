<?php

declare(strict_types=1);

namespace Componenta\App\WebSocket;

use Componenta\App\AppInterface;
use Componenta\Config\Config;
use Componenta\WebSocket\Application\WebSocketApplicationInterface;
use Componenta\WebSocket\Application\WebSocketApplicationResolverInterface;
use Componenta\WebSocket\Config\ConfigKey;
use Componenta\WebSocket\Transport\WebSocketServerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class App implements AppInterface
{
    private WebSocketApplicationInterface $application;

    public function __construct(
        private readonly WebSocketServerInterface $server,
        private readonly WebSocketApplicationResolverInterface $resolver,
        WebSocketApplicationInterface $defaultApplication,
    ) {
        $this->application = $defaultApplication;
    }

    public function run(): ?int
    {
        $this->server->run($this->application);

        return null;
    }

    public function setApplication(mixed $application): void
    {
        $this->application = $this->resolver->resolve($application);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function createFromContainer(ContainerInterface $container): self
    {
        $config = $container->get(Config::class);
        $app = new self(
            $container->get(WebSocketServerInterface::class),
            $container->get(WebSocketApplicationResolverInterface::class),
            $container->get(WebSocketApplicationInterface::class),
        );
        $application = $config->get(ConfigKey::APPLICATION, null);

        if ($application !== null) {
            $app->setApplication($application);
        }

        return $app;
    }
}
