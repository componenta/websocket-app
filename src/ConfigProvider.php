<?php

declare(strict_types=1);

namespace Componenta\App\WebSocket;

use Componenta\App\ConfigKey as AppConfigKey;
use Componenta\App\WebSocket\Boot\WebSocketBootTargetAdapter;
use Componenta\App\WebSocket\Boot\WebSocketBootloader;
use Componenta\Config\ConfigProvider as BaseConfigProvider;

final class ConfigProvider extends BaseConfigProvider
{
    protected function getProviders(): array
    {
        return [
            new \Componenta\WebSocket\ConfigProvider(),
        ];
    }

    protected function getConfig(): array
    {
        return [
            AppConfigKey::APP_ADAPTERS => [
                WebSocketAppAdapter::class,
            ],
            AppConfigKey::BOOT_TARGET_ADAPTERS => [
                WebSocketBootTargetAdapter::class,
            ],
            AppConfigKey::BOOTLOADERS => [
                WebSocketBootloader::class,
            ],
        ];
    }

    protected function getAutowires(): array
    {
        return [
            App::class,
            WebSocketAppAdapter::class,
            WebSocketBootTargetAdapter::class,
            WebSocketBootloader::class,
        ];
    }
}
