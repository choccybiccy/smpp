<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class BindTransmitter.
 */
class BindTransmitter extends AbstractPdu
{
    /**
     * @return int
     */
    public function getCommandId()
    {
        return 0x00000002;
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'bind_transmitter';
    }
}
