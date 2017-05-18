<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class EnquireLink.
 */
class EnquireLink extends AbstractPdu
{
    /**
     * @return int
     */
    public function getCommandId()
    {
        return 0x00000015;
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'enquire_link';
    }
}
