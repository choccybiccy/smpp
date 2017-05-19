<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class BindReceiverResp.
 */
class BindReceiverResp extends AbstractPdu
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
        return 0x80000001;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'bind_received_resp';
    }
}
