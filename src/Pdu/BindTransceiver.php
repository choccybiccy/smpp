<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class BindTransceiver.
 */
class BindTransceiver extends AbstractPdu
{
    /**
     * @return int
     */
    public function getCommandId()
    {
        return 0x00000009;
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'bind_transceiver';
    }
}
