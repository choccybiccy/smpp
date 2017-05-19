<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class CancelSm.
 */
class CancelSm extends AbstractPdu
{
    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000008;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'cancel_sm';
    }
}
