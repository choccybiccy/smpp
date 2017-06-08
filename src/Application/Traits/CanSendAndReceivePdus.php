<?php

namespace Choccybiccy\Smpp\Application\Traits;

use Choccybiccy\Smpp\Pdu\AbstractPdu;
use React\Socket\ConnectionInterface;

trait CanSendAndReceivePdus
{
    /**
     * @param ConnectionInterface $connection
     * @param AbstractPdu $pdu
     *
     * @return AbstractPdu
     */
    public function sendPdu(ConnectionInterface $connection, AbstractPdu $pdu)
    {
        $data = $pdu->encode();
        $connection->write($data);
        return $pdu;
    }
}