<?php

namespace Choccybiccy\Smpp\Application;

use Choccybiccy\Smpp\PduFactory;
use League\Event\Emitter;
use League\Event\Event;
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
     * @var PduFactory
     */
    protected $pduFactory;

    /**
     * @var Emitter
     */
    protected $emitter;

    /**
     * ServerApplication constructor.
     * @param LoopInterface $loop
     * @param ServerInterface $server
     * @param PduFactory $pduFactory
     * @param Emitter $emitter
     */
    public function __construct(LoopInterface $loop, ServerInterface $server, PduFactory $pduFactory, Emitter $emitter)
    {
        $this->loop = $loop;
        $this->server = $server;
        $this->pduFactory = $pduFactory;
        $this->emitter = $emitter;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $this->server->on('connection', function (ConnectionInterface $connection) {
            $this->emitter->emit(Event::named('server.connection'), $connection);
            $connection->on('data', function ($data) use ($connection) {
                $pdus = $this->pduFactory->createFromData($data);
                if ($pdus) {
                    foreach ($pdus as $pdu) {
                        $this->emitter->emit('server.pdu.' . $pdu->getCommandName(), $pdu, $connection);
                    }
                }
            });
        });
    }
}
