<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class DeliverSm.
 */
class DeliverSm extends AbstractPdu
{
    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000005;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'deliver_sm';
    }
}
