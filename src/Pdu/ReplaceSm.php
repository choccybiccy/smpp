<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class ReplaceSm.
 */
class ReplaceSm extends AbstractPdu
{
    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000007;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'replace_sm';
    }
}
