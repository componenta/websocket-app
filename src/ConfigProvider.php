<?php

declare(strict_types=1);

namespace Componenta\App\WebSocket;

use Componenta\App\ConfigKey as AppConfigKey;
use Componenta\App\Scope;
use Componenta\App\WebSocket\Boot\WebSocketBootTargetAdapter;
use Componenta\App\WebSocket\Boot\WebSocketBootloader;
use Componenta\Config\ConfigProvider as BaseConfigProvider;
use Psr\Container\ContainerInterface;

final class ConfigProvider extends BaseConfigProvider
{
    /**
     * @return array<string, mixed>
     */
    protected function getConfig(): array
    {
        return [
            AppConfigKey::APP_BY_SCOPE => [
                Scope::WEBSOCKET->value => App::class,
            ],
            AppConfigKey::BOOT_TARGET_ADAPTERS => [
                WebSocketBootTargetAdapter::class,
            ],
            AppConfigKey::BOOTLOADERS => [
                WebSocketBootloader::class,
            ],
        ];
    }

    protected function getFactories(): array
    {
        return [
            App::class => static fn(ContainerInterface $container): App
                => App::createFromContainer($container),
        ];
    }
}
