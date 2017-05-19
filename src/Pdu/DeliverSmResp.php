<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class DeliverSm.
 */
class DeliverSmResp extends AbstractPdu
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
        return 0x80000005;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'deliver_sm_resp';
    }
}
