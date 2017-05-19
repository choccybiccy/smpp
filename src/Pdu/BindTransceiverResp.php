<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class BindTransceiverResp.
 */
class BindTransceiverResp extends AbstractPdu
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
        return 0x80000009;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'bind_transceiver_resp';
    }
}
