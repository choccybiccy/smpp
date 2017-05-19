<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class EnquireLinkResp.
 */
class EnquireLinkResp extends AbstractPdu
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
        return 0x80000015;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'enquire_link_resp';
    }
}
