<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class CancelSmResp.
 */
class CancelSmResp extends AbstractPdu
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
        return 0x80000008;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'cancel_sm_resp';
    }
}
