<?php

declare(strict_types=1);

namespace Componenta\App\WebSocket\Tests;

use Componenta\App\AppInterface;
use Componenta\App\Scope;
use Componenta\App\WebSocket\App;
use Componenta\App\WebSocket\Boot\Target\WebSocketBootTargetInterface;
use Componenta\App\WebSocket\Boot\WebSocketBootTargetAdapter;
use Componenta\App\WebSocket\WebSocketAppAdapter;
use Componenta\Config\Config;
use Componenta\WebSocket\Application\Error\WebSocketErrorContextInterface;
use Componenta\WebSocket\Application\WebSocketApplicationInterface;
use Componenta\WebSocket\Application\WebSocketApplicationResolverInterface;
use Componenta\WebSocket\Connection\ConnectionInterface;
use Componenta\WebSocket\Protocol\CloseInfo;
use Componenta\WebSocket\Protocol\Message;
use Componenta\WebSocket\Transport\WebSocketServerInterface;
use LogicException;
use Psr\Container\ContainerInterface;
use RuntimeException;

final class WebSocketAppTestContainer implements ContainerInterface
{
    /**
     * @param array<string, mixed> $entries
     */
    public function __construct(
        private readonly array $entries,
    ) {}

    public function get(string $id): mixed
    {
        return $this->entries[$id] ?? throw new RuntimeException("Missing test entry {$id}.");
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->entries);
    }
}

final class WebSocketAppTestServer implements WebSocketServerInterface
{
    public ?WebSocketApplicationInterface $application = null;

    public function run(WebSocketApplicationInterface $application): void
    {
        $this->application = $application;
    }

    public function stop(): void {}
}

final readonly class WebSocketAppTestResolver implements WebSocketApplicationResolverInterface
{
    public function resolve(mixed $application): WebSocketApplicationInterface
    {
        if (!$application instanceof WebSocketApplicationInterface) {
            throw new RuntimeException('Invalid WebSocket application.');
        }

        return $application;
    }
}

final readonly class WebSocketAppTestApplication implements WebSocketApplicationInterface
{
    public function connected(ConnectionInterface $connection): void {}

    public function received(ConnectionInterface $connection, Message $message): void {}

    public function disconnected(ConnectionInterface $connection, CloseInfo $close): void {}

    public function failed(WebSocketErrorContextInterface $context): void {}
}

final class WebSocketAppTestFallbackApp implements AppInterface
{
    public function run(): ?int
    {
        return 0;
    }
}

describe('websocket app integration', function (): void {
    it('wraps the websocket app in a narrow configuration adapter', function (): void {
        $server = new WebSocketAppTestServer();
        $defaultApplication = new WebSocketAppTestApplication();
        $configuredApplication = new WebSocketAppTestApplication();
        $app = new App(
            $server,
            new WebSocketAppTestResolver(),
            $defaultApplication,
        );

        $target = (new WebSocketBootTargetAdapter())->create($app, Scope::WEBSOCKET);

        expect($target)->toBeInstanceOf(WebSocketBootTargetInterface::class);

        $target->application = $configuredApplication;
        $app->run();

        expect($server->application)->toBe($configuredApplication);
    });

    it('rejects non websocket apps for websocket scope', function (): void {
        expect(fn () => (new WebSocketBootTargetAdapter())->create(new WebSocketAppTestFallbackApp(), Scope::WEBSOCKET))
            ->toThrow(LogicException::class, 'expects app');
    });

    it('does not support non websocket boot target scopes', function (): void {
        $adapter = new WebSocketBootTargetAdapter();

        expect($adapter->supports(Scope::WEBSOCKET))->toBeTrue()
            ->and($adapter->supports(Scope::HTTP))->toBeFalse();
    });

    it('creates the websocket app only for websocket scope', function (): void {
        $server = new WebSocketAppTestServer();
        $container = new WebSocketAppTestContainer([
            Config::class => new Config([]),
            WebSocketServerInterface::class => $server,
            WebSocketApplicationResolverInterface::class => new WebSocketAppTestResolver(),
            WebSocketApplicationInterface::class => new WebSocketAppTestApplication(),
        ]);
        $adapter = new WebSocketAppAdapter();

        $webSocketApp = $adapter->createApp(Scope::WEBSOCKET, $container, new Config([]));

        expect($webSocketApp)->toBeInstanceOf(App::class)
            ->and($adapter->supports(Scope::WEBSOCKET))->toBeTrue()
            ->and($adapter->supports(Scope::HTTP))->toBeFalse();
    });
});
