<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class DeliverSm.
 */
class DeliverSm extends DataSm
{
    /**
     * @return int
     */
    public function getCommandId()
    {
        return 0x00000005;
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'deliver_sm';
    }
}
