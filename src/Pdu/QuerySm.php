<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class QuerySm.
 */
class QuerySm extends AbstractPdu
{
    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000003;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'query_sm';
    }
}
