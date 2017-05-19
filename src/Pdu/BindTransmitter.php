<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class BindTransmitter.
 */
class BindTransmitter extends BindReceiver
{
    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000002;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'bind_transmitter';
    }
}
