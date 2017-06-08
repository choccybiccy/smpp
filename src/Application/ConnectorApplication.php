<?php

namespace Choccybiccy\Smpp\Application;

use Choccybiccy\Smpp\Application\Traits\CanSendAndReceivePdus;
use Choccybiccy\Smpp\Connector\Connection;
use Choccybiccy\Smpp\Pdu\BindTransceiver;
use Choccybiccy\Smpp\PduFactory;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\ConnectorInterface;

class ConnectorApplication implements ApplicationInterface
{
    use CanSendAndReceivePdus;

    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var ConnectorInterface
     */
    protected $connector;

    /**
     * @var Connection[]
     */
    protected $connections;

    /**
     * @var PduFactory
     */
    protected $pduFactory;

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * ConnectorApplication constructor.
     * @param LoopInterface $loop
     * @param ConnectorInterface $connector
     * @param Connection[] $connections
     * @param PduFactory $pduFactory
     * @param LoggerInterface $log
     */
    public function __construct(
        LoopInterface $loop,
        ConnectorInterface $connector,
        array $connections,
        PduFactory $pduFactory,
        LoggerInterface $log
    ) {
        $this->loop = $loop;
        $this->connector = $connector;
        $this->connections = $connections;
        $this->pduFactory = $pduFactory;
        $this->log = $log;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        foreach ($this->connections as $connectionDetails) {
            $this->connector->connect($connectionDetails->getAddress())
                ->then(function(ConnectionInterface $connection) use ($connectionDetails) {
                    $this->log->info('Connected to ' . $connection->getRemoteAddress());
                    $this->bind($connection, $connectionDetails);
                });
        }
    }

    /**
     * @param ConnectionInterface $connection
     * @param Connection $connectionDetails
     */
    protected function bind(ConnectionInterface $connection, Connection $connectionDetails)
    {
        $pdu = $this->sendPdu(
            $connection,
            $this->pduFactory->createFromCommand($connectionDetails->getBind(), $connectionDetails->getPdu())
        );
        $this->log->info('>>> ' . bin2hex($pdu->encode()));
    }
}