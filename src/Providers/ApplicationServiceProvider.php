<?php

namespace Choccybiccy\Smpp\Providers;

use Choccybiccy\Smpp\AbstractApplication;
use Choccybiccy\Smpp\EsmeApplication;
use Choccybiccy\Smpp\SmscApplication;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Logger;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;
use React\Socket\Connector;

class ApplicationServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        EsmeApplication::class,
        SmscApplication::class,
        AbstractApplication::class,
        LoopInterface::class,
        Connector::class,
    ];

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->registerApplications();
    }

    /**
     * Register application services.
     */
    protected function registerApplications()
    {
        $container = $this->container;
        $this->container->add(EsmeApplication::class, function () use ($container) {
            $loop = LoopFactory::create();
            return new EsmeApplication(
                $loop,
                new Connector($loop),
                new Logger('esme')
            );
        });
        $this->container->add(SmscApplication::class, function () use ($container) {
            return new SmscApplication(
                LoopFactory::create(),
                new Logger('smsc')
            );
        });
    }
}