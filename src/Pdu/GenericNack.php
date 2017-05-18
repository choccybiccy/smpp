<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class GenericNack.
 */
class GenericNack extends AbstractPdu
{
    /**
     * @return int
     */
    public function getCommandId()
    {
        return 0x00000000;
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'generic_nack';
    }
}
