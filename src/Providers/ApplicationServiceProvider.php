<?php

namespace Choccybiccy\Smpp\Providers;

use Choccybiccy\Smpp\AbstractApplication;
use Choccybiccy\Smpp\Application;
use Choccybiccy\Smpp\Config;
use Choccybiccy\Smpp\Connector\Connection;
use Choccybiccy\Smpp\EsmeApplication;
use Choccybiccy\Smpp\Monolog\Processor\ApplicationContextProcessor;
use Choccybiccy\Smpp\PduFactory;
use Choccybiccy\Smpp\SmscApplication;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Psr\Log\LoggerInterface;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
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

        $container->add(Application::class, function () use ($container, $config) {
            $loop = Factory::create();
            $log = $container->get('logger');
            return new Application(
                $loop,
                new Application\ServerApplication(
                    $loop,
                    new Server($config->get('server.listen_address'), $loop),
                    $log
                ),
                new Application\ConnectorApplication(
                    $loop,
                    new Connector($loop, $config->get('connector.options')),
                    Connection::makeFromArray($config->get('connector.connections')),
                    new PduFactory(),
                    $log
                ),
                $log
            );
        });
    }
}