<?php

namespace Choccybiccy\Smpp\Providers;

use Choccybiccy\Smpp\Application;
use Choccybiccy\Smpp\Config;
use Choccybiccy\Smpp\Connector\Connection;
use Choccybiccy\Smpp\PduFactory;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Event\Emitter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use React\EventLoop\Factory;
use React\Socket\Connector;
use React\Socket\Server;

class ApplicationServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        Application::class,
        Config::class,
        LoggerInterface::class,
        Emitter::class,
    ];

    /**
     * @var string
     */
    protected $configPath;

    /**
     * ApplicationServiceProvider constructor.
     * @param string $configPath
     */
    public function __construct($configPath)
    {
        $this->configPath = $configPath;
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $container = $this->container;
        $config = Config::loadFromYaml($this->configPath);
        $container->share('config', $config);
        $container->share('logger.handler', function () use ($config) {
            return new StreamHandler($config->get('log.handler'), $config->get('log.level'));
        });
        $container->share('logger', function () use ($container) {
            return new Logger(
                $container->get('config')->get('log.name'),
                [$container->get('logger.handler')]
            );
        });
        $container->share('event.emitter', function () use ($container, $config) {
            $emitter = new Emitter();
            foreach ($config->get('server.events') as $event => $listener) {
                $emitter->addListener('server.' . $event, $container->get($listener));
            }
            foreach ($config->get('connector.events') as $event => $listener) {
                $emitter->addListener('connector.' . $event, $container->get($listener));
            }
            return $emitter;
        });

        $container->add(Application::class, function () use ($container, $config) {
            $loop = Factory::create();
            $eventEmitter = $container->get('event.emitter');
            return new Application(
                $loop,
                new Application\ServerApplication(
                    $loop,
                    new Server($config->get('server.listen_address'), $loop),
                    new PduFactory(),
                    $eventEmitter
                ),
                new Application\ConnectorApplication(
                    $loop,
                    new Connector($loop, $config->get('connector.options')),
                    Connection::makeFromArray($config->get('connector.connections')),
                    new PduFactory(),
                    $eventEmitter
                )
            );
        });
    }
}
