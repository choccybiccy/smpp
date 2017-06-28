<?php

namespace Choccybiccy\Smpp;

use Choccybiccy\Smpp\Application\ApplicationInterface;
use Choccybiccy\Smpp\Application\ConnectorApplication;
use Choccybiccy\Smpp\Application\ServerApplication;
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
     * Application constructor.
     * @param LoopInterface $loop
     * @param ServerApplication $serverApplication
     * @param ConnectorApplication $connectorApplication
     */
    public function __construct(
        LoopInterface $loop,
        ServerApplication $serverApplication,
        ConnectorApplication $connectorApplication
    ) {
        $this->loop = $loop;
        $this->serverApplication = $serverApplication;
        $this->connectorApplication = $connectorApplication;
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
