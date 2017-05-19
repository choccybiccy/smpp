<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class BindTransceiver.
 */
class BindTransceiver extends BindReceiver
{
    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000009;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'bind_transceiver';
    }
}
