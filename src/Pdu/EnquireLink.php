<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class EnquireLink.
 */
class EnquireLink extends AbstractPdu
{
    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000015;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'enquire_link';
    }
}
