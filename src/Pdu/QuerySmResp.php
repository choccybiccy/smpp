<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class ReplaceSm.
 */
class QuerySmResp extends AbstractPdu
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
        return 0x80000003;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'query_sm_resp';
    }
}
