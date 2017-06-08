<?php

namespace Choccybiccy\Smpp\Application;

use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\ServerInterface;

class ServerApplication implements ApplicationInterface
{
    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var ServerInterface
     */
    protected $server;

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * ServerApplication constructor.
     * @param LoopInterface $loop
     * @param ServerInterface $server
     * @param LoggerInterface $log
     */
    public function __construct(LoopInterface $loop, ServerInterface $server, LoggerInterface $log)
    {
        $this->loop = $loop;
        $this->server = $server;
        $this->log = $log;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $this->log->info('Listening for connections on ' . $this->server->getAddress());
        $this->server->on('connection', function (ConnectionInterface $connection) {
            $this->handleConnection($connection);
        });
    }

    /**
     * @param ConnectionInterface $connection
     */
    protected function handleConnection(ConnectionInterface $connection)
    {
        $this->log->info('Connection from ' . $connection->getRemoteAddress());
        $connection->on('data', function ($data) {
           $this->log->info('<<< ' . bin2hex($data));
        });
    }
}
