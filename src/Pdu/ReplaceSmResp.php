<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class ReplaceSm.
 */
class ReplaceSmResp extends AbstractPdu
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
        return 0x80000007;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'replace_sm_resp';
    }
}
