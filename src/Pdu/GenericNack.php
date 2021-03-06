<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class GenericNack.
 */
class GenericNack extends AbstractPdu
{
    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000000;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'generic_nack';
    }
}
