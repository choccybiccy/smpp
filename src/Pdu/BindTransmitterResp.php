<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class BindTransmitterResp.
 */
class BindTransmitterResp extends AbstractPdu
{
    /**
     * @inheritDoc
     */
    protected $isResponse = true;

    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x80000002;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'bind_transmitter_resp';
    }
}
