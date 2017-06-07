<?php

namespace Choccybiccy\Smpp;

use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\Connector;
use React\Socket\TcpServer;
use Symfony\Component\Console\Input\InputArgument;

class EsmeApplication extends AbstractApplication
{
    /**
     * @var Connector
     */
    protected $connector;

    /**
     * @var array
     */
    protected $inputArguments = [
        ['remote', InputArgument::REQUIRED, 'Remote address'],
    ];

    /**
     * EsmeApplication constructor.
     *
     * @param LoopInterface   $loop
     * @param Connector       $connector
     * @param LoggerInterface $log
     */
    public function __construct(LoopInterface $loop, Connector $connector, LoggerInterface $log)
    {
        parent::__construct($loop, $log);
        $this->connector = $connector;
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        $this->connector->connect($this->input->getArgument('remote'))
            ->then(function (ConnectionInterface $connection) {
                $this->log->info('Connection established with ' . $connection->getRemoteAddress());
                $connection->on('data', function ($data) use ($connection) {
                    $this->handleData($connection, $data);
                });
            });
    }

    /**
     * Handle incoming data.
     *
     * @param ConnectionInterface $connection
     * @param                     $data
     */
    protected function handleData(ConnectionInterface $connection, $data)
    {
        $this->log->info('<<< ' . bin2hex($data));
    }
}
