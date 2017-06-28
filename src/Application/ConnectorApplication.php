<?php

namespace Choccybiccy\Smpp\Application;

use Choccybiccy\Smpp\Application\Traits\CanSendAndReceivePdus;
use Choccybiccy\Smpp\Connector\Connection;
use Choccybiccy\Smpp\PduFactory;
use League\Event\Emitter;
use League\Event\Event;
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
     * @var Emitter
     */
    protected $emitter;

    /**
     * ConnectorApplication constructor.
     * @param LoopInterface $loop
     * @param ConnectorInterface $connector
     * @param Connection[] $connections
     * @param PduFactory $pduFactory
     * @param Emitter $emitter
     */
    public function __construct(
        LoopInterface $loop,
        ConnectorInterface $connector,
        array $connections,
        PduFactory $pduFactory,
        Emitter $emitter
    ) {
        $this->loop = $loop;
        $this->connector = $connector;
        $this->connections = $connections;
        $this->pduFactory = $pduFactory;
        $this->emitter = $emitter;
    }

    /**
     * @return void
     */
    public function run()
    {
        foreach ($this->connections as $connectionDetails) {
            $this->connector->connect($connectionDetails->getAddress())
                ->then(function (ConnectionInterface $connection) use ($connectionDetails) {
                    $this->handleConnection($connection);
                    $this->bind($connection, $connectionDetails);
                });
        }
    }

    protected function handleConnection(ConnectionInterface $connection)
    {
        $this->emitter->emit(Event::named('connector.connection'), $connection);
        $connection->on('close', function () use ($connection) {
            $this->emitter->emit(Event::named('connector.connection.close'), $connection);
        });
    }

    /**
     * @param ConnectionInterface $connection
     * @param Connection $connectionDetails
     */
    protected function bind(ConnectionInterface $connection, Connection $connectionDetails)
    {
        $pdu = $this->sendPdu(
            $connection,
            $this->pduFactory->createFromCommand($connectionDetails->getType(), [
                'systemId' => $connectionDetails->getSystemId(),
                'password' => $connectionDetails->getPassword(),
            ])
        );
        $this->emitter->emit(Event::named('connector.pdu.' . $pdu->getCommandName()), $pdu, $connection);
    }
}
