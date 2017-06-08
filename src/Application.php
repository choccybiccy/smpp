<?php

namespace Choccybiccy\Smpp;

use Choccybiccy\Smpp\Application\ApplicationInterface;
use Choccybiccy\Smpp\Application\ConnectorApplication;
use Choccybiccy\Smpp\Application\ServerApplication;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;

class Application implements ApplicationInterface
{
    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var ServerApplication
     */
    protected $serverApplication;

    /**
     * @var ConnectorApplication
     */
    protected $connectorApplication;

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * Application constructor.
     * @param LoopInterface $loop
     * @param ServerApplication $serverApplication
     * @param ConnectorApplication $connectorApplication
     * @param LoggerInterface $log
     */
    public function __construct(
        LoopInterface $loop,
        ServerApplication $serverApplication,
        ConnectorApplication $connectorApplication,
        LoggerInterface $log
    ) {
        $this->loop = $loop;
        $this->serverApplication = $serverApplication;
        $this->connectorApplication = $connectorApplication;
        $this->log = $log;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $this->serverApplication->run();
        $this->connectorApplication->run();
        $this->loop->run();
    }

}