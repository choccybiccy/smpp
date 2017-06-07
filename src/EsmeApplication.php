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
        ['remote', InputArgument::OPTIONAL, 'Remote address'],
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
        $remote = $this->input->getArgument('remote') ?: $this->config->get('esme.remote_address');
        if (!$remote) {
            throw new \RuntimeException('No remote address specified');
        }
        $this->log->info('Connecting to ' . $remote);
        $this->connector->connect($remote)
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
