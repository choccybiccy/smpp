<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class SubmitSm.
 */
class SubmitSm extends DataSm
{
    /**
     * @return int
     */
    public function getCommandId()
    {
        return 0x00000004;
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'submit_sm';
    }
}
