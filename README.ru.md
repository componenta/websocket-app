# Componenta WebSocket App

Интеграция `componenta/websocket-server` с приложением. Пакет добавляет адаптер WebSocket-приложения, точку загрузки и загрузчик для рантайм-области `Scope::WEBSOCKET`.

## Установка

```bash
composer require componenta/websocket-app
```

Пакет публикует `Componenta\App\WebSocket\ConfigProvider` через метаданные Composer и подключает `Componenta\WebSocket\ConfigProvider`.

## Что регистрирует пакет

`ConfigProvider` регистрирует:

| Сервис или ключ конфигурации | Назначение |
|---|---|
| `WebSocketAppAdapter` | Создает WebSocket-приложение для `Scope::WEBSOCKET`. |
| `WebSocketBootTargetAdapter` | Создает точку загрузки WebSocket-конфигурации. |
| `WebSocketBootloader` | Загружает `config/websocket.php`, если файл существует. |
| `App` | Запускает настроенное WebSocket-приложение сервера. |

## Файл конфигурации

`WebSocketBootloader` подключает `config/websocket.php`, если файл существует. Файл получает `$app` как `WebSocketBootTargetInterface` и может установить приложение:

```php
use Componenta\App\WebSocket\Boot\Target\WebSocketBootTargetInterface;

/** @var WebSocketBootTargetInterface $app */
$app->application = App\WebSocket\ChatApplication::class;
```

Значением может быть `WebSocketApplicationInterface`, id сервиса контейнера, `MessageRouterInterface` или callable.

## Связанные пакеты

- [`componenta/websocket-server`](https://github.com/componenta/websocket-server/blob/main/README.ru.md) дает сервер, протокол, соединения, сокеты и контракты приложения.
- [`componenta/skeleton`](https://github.com/componenta/skeleton/blob/main/README.ru.md) содержит WebSocket-пресет с `bin/websocket.php`.
