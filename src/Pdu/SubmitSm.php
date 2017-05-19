<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class SubmitSm.
 */
class SubmitSm extends AbstractPdu
{
    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000004;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'submit_sm';
    }
}
