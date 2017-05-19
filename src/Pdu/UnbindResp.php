<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class UnbindResp.
 */
class UnbindResp extends AbstractPdu
{
    /**
     * @inheritDoc
     */
    protected $isResponse = true;

    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x80000006;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'unbind_resp';
    }
}
