# Componenta WebSocket App

Application integration for `componenta/websocket-server`. The package adds a WebSocket application adapter, boot target, and bootloader for the `Scope::WEBSOCKET` runtime.

## Installation

```bash
composer require componenta/websocket-app
```

The package exposes `Componenta\App\WebSocket\ConfigProvider` through Composer metadata and includes `Componenta\WebSocket\ConfigProvider`.

## Registered Services

`ConfigProvider` registers:

| Service or config key | Purpose |
|---|---|
| `WebSocketAppAdapter` | Creates a WebSocket app for `Scope::WEBSOCKET`. |
| `WebSocketBootTargetAdapter` | Creates the boot target for WebSocket configuration. |
| `WebSocketBootloader` | Loads `config/websocket.php` when it exists. |
| `App` | Runs the configured WebSocket server application. |

## Configuration File

`WebSocketBootloader` requires `config/websocket.php` if it exists. The file receives `$app` as `WebSocketBootTargetInterface` and can set the application:

```php
use Componenta\App\WebSocket\Boot\Target\WebSocketBootTargetInterface;

/** @var WebSocketBootTargetInterface $app */
$app->application = App\WebSocket\ChatApplication::class;
```

The assigned value may be a `WebSocketApplicationInterface`, a container service id, a `MessageRouterInterface`, or a callable.

## Related Packages

- [`componenta/websocket-server`](https://github.com/componenta/websocket-server/blob/main/README.md) provides the server, protocol, connection, socket, and application contracts.
- [`componenta/skeleton`](https://github.com/componenta/skeleton/blob/main/README.md) has a WebSocket preset with `bin/websocket.php`.
