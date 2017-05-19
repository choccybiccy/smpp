<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class SubmitSmResp.
 */
class SubmitSmResp extends AbstractPdu
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
        return 0x80000004;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'submit_sm_resp';
    }
}
