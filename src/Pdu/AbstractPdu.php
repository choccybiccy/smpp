<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class AbstractPdu.
 */
abstract class AbstractPdu
{

    const DATA_TYPE_INT = 'int';
    const DATA_TYPE_C_OCTET_STR = 'c_octet';
    const DATA_TYPE_OCTET_STR = 'octet';
    const DATA_TYPE_DATETIME = 'datetime';

    const ESME_ROK = 0x00000000;
    const ESME_RINVMSGLEN = 0x00000001;
    const ESME_RINVCMDLEN = 0x00000002;
    const ESME_RINVCMDID = 0x00000003;
    const ESME_RINVBNDSTS = 0x00000004;
    const ESME_RALYBND = 0x00000005;
    const ESME_RINVPRTFLG = 0x00000006;
    const ESME_RINVREGDLVFLG = 0x00000007;
    const ESME_RSYSERR = 0x00000008;
    const ESME_RINVSRCADR = 0x0000000A;
    const ESME_RINVDSTADR = 0x0000000B;
    const ESME_RINVMSGID = 0x0000000C;
    const ESME_RBINDFAIL = 0x0000000D;
    const ESME_RINVPASWD = 0x0000000E;
    const ESME_RINVSYSID = 0x0000000F;
    const ESME_RCANCELFAIL = 0x00000011;
    const ESME_RREPLACEFAIL = 0x00000013;
    const ESME_RMSGQFUL = 0x00000014;
    const ESME_RINVSERTYP = 0x00000015;
    const ESME_RINVNUMDESTS = 0x00000033;
    const ESME_RINVDLNAME = 0x00000034;
    const ESME_RINVDESTFLAG = 0x00000040;
    const ESME_RINVSUBREP = 0x00000042;
    const ESME_RINVESMCLASS = 0x00000043;
    const ESME_RCNTSUBDL = 0x00000044;
    const ESME_RSUBMITFAIL = 0x00000045;
    const ESME_RINVSRCTON = 0x00000048;
    const ESME_RINVSRCNPI = 0x00000049;
    const ESME_RINVDSTTON = 0x00000050;
    const ESME_RINVDSTNPI = 0x00000051;
    const ESME_RINVSYSTYP = 0x00000053;
    const ESME_RINVREPFLAG = 0x00000054;
    const ESME_RINVNUMMSGS = 0x00000055;
    const ESME_RTHROTTLED = 0x00000058;
    const ESME_RINVSCHED = 0x00000061;
    const ESME_RINVEXPIRY = 0x00000062;
    const ESME_RINVDFTMSGID = 0x00000063;
    const ESME_RX_T_APPN = 0x00000064;
    const ESME_RX_P_APPN = 0x00000065;
    const ESME_RX_R_APPN = 0x00000066;
    const ESME_RQUERYFAIL = 0x00000067;
    const ESME_RINVOPTPARSTREAM = 0x000000C0;
    const ESME_ROPTPARNOTALLWD = 0x000000C1;
    const ESME_RINVPARLEN = 0x000000C2;
    const ESME_RMISSINGOPTPARAM = 0x000000C3;
    const ESME_RINVOPTPARAMVAL = 0x000000C4;
    const ESME_RDELIVERYFAILURE = 0x000000FE;
    const ESME_RUNKNOWNERR = 0x000000FF;

    const TON_UNKNOWN = 0x00000000;
    const TON_INTERNATIONAL = 0x00000001;
    const TON_NATIONAL = 0x00000010;
    const TON_NETWORK_SPECIFIC = 0x00000011;
    const TON_SUBSCRIBER_NUMBER = 0x00000100;
    const TON_ALPHANUMERIC = 0x00000101;
    const TON_ABBREVIATED = 0x00000110;

    const NPI_UNKNOWN = 0x00000000;
    const NPI_ISDN = 0x00000001;
    const NPI_DATA = 0x00000011;
    const NPI_TELEX = 0x00000100;
    const NPI_LAND_LINE = 0x00000110;
    const NPI_NATIONAL_ = 0x00001000;
    const NPI_PRIVATE = 0x00001001;
    const NPI_ERMES = 0x00001010;
    const NPI_IP = 0x00001110;
    const NPI_WAP = 0x00010010;

    /**
     * @var int
     */
    protected $commandStatus;

    /**
     * @var int
     */
    protected $sequenceNumber;

    /**
     * @var array
     */
    protected $packetHeaderFormat = [
        [
            'type' => self::DATA_TYPE_INT,
            'name' => 'commandLength',
        ],
        [
            'type' => self::DATA_TYPE_INT,
            'name' => 'commandId',
        ],
        [
            'type' => self::DATA_TYPE_INT,
            'name' => 'commandStatus',
        ],
        [
            'type' => self::DATA_TYPE_INT,
            'name' => 'sequenceNumber',
        ],
    ];

    /**
     * @var array
     */
    protected $packetBodyFormat = [];

    /**
     * @return string
     */
    public function encode()
    {

    }

    /**
     * @return int
     */
    public function getCommandStatus()
    {
        return $this->commandStatus;
    }

    /**
     * @param int $commandStatus
     * @return AbstractPdu
     */
    public function setCommandStatus($commandStatus)
    {
        $this->commandStatus = $commandStatus;
        return $this;
    }

    /**
     * @return int
     */
    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
    }

    /**
     * @param int $sequenceNumber
     */
    public function setSequenceNumber($sequenceNumber)
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    /**
     * @return int
     */
    abstract public function getCommandId();

    /**
     * @return string
     */
    abstract public function getCommandName();
}
