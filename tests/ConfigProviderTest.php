<?php

declare(strict_types=1);

use Componenta\App\ConfigKey as AppConfigKey;
use Componenta\App\Scope;
use Componenta\App\WebSocket\App;
use Componenta\App\WebSocket\Boot\WebSocketBootTargetAdapter;
use Componenta\App\WebSocket\Boot\WebSocketBootloader;
use Componenta\App\WebSocket\ConfigProvider;
use Componenta\Config\ConfigKey as DependencyConfigKey;

it('registers the WebSocket scope through the current app contract', function (): void {
    $config = (new ConfigProvider())();
    $dependencies = $config[DependencyConfigKey::DEPENDENCIES];

    expect($config[AppConfigKey::APP_BY_SCOPE])->toBe([
        Scope::WEBSOCKET->value => App::class,
    ])
        ->and($config[AppConfigKey::BOOT_TARGET_ADAPTERS])->toBe([
            WebSocketBootTargetAdapter::class,
        ])
        ->and($config[AppConfigKey::BOOTLOADERS])->toBe([
            WebSocketBootloader::class,
        ])
        ->and($dependencies[DependencyConfigKey::FACTORIES])->toHaveKey(App::class)
        ->and($dependencies)->not->toHaveKey('autowires');
});
