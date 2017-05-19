<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class Unbind.
 */
class Unbind extends AbstractPdu
{
    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000006;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'unbind';
    }
}
