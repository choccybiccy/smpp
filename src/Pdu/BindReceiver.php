<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class BindReceiver.
 */
class BindReceiver extends AbstractPdu
{
    /**
     * @return int
     */
    public function getCommandId()
    {
        return 0x00000001;
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'bind_receiver';
    }
}
