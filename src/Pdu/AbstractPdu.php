<?php

namespace Choccybiccy\Smpp\Pdu;

use Choccybiccy\Smpp\Pdu\Exception\MalformedPduException;
use Choccybiccy\Smpp\Pdu\Exception\PduException;

/**
 * Class AbstractPdu.
 */
abstract class AbstractPdu
{
    const HEADER_SIZE = 16;

    const DATA_TYPE_INT = 'int';
    const DATA_TYPE_VARCHAR = 'varchar';
    const DATA_TYPE_CHAR = 'char';
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
    const NPI_NATIONAL = 0x00001000;
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
     * @var bool
     */
    protected $isResponse = false;

    /**
     * @var array
     */
    protected $packetHeaderFormat = [
        [
            'type' => self::DATA_TYPE_INT,
            'field' => 'commandLength',
            'length' => 4,
        ],
        [
            'type' => self::DATA_TYPE_INT,
            'field' => 'commandId',
            'length' => 4,
        ],
        [
            'type' => self::DATA_TYPE_INT,
            'field' => 'commandStatus',
            'length' => 4,
        ],
        [
            'type' => self::DATA_TYPE_INT,
            'field' => 'sequenceNumber',
            'length' => 4,
        ],
    ];

    /**
     * @var array
     */
    protected $packetBodyFormat = [];

    /**
     * AbstractPdu constructor.
     *
     * @param array|null $parameters
     */
    public function __construct(array $parameters = null)
    {
        if ($parameters) {
            $this->set($parameters);
        }
    }

    /**
     * @param array|string $property
     * @param mixed        $value
     *
     * @return $this
     */
    public function set($property, $value = null)
    {
        if (is_array($property)) {
            foreach ($property as $key => $value) {
                $this->set($key, $value);
            }
        } else {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
        return $this;
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
        return null;
    }

    /**
     * @return string
     *
     * @throws PduException
     */
    public function encode()
    {
        $packetFormat = array_merge(array_slice($this->packetHeaderFormat, 2), $this->packetBodyFormat);
        $data = $this->encodeSection(self::DATA_TYPE_INT, $this->getCommandId(), 4);
        foreach ($packetFormat as $section) {
            $length = array_key_exists('length', $section) ? $section['length'] : null;
            $data.= $this->encodeSection($section['type'], $this->get($section['field']), $length);
        }
        return pack('N', strlen($data)+4) . $data;
    }

    /**
     * @param $type
     * @param $value
     *
     * @return string
     */
    protected function encodeSection($type, $value, $length)
    {
        switch ($type)
        {
            case self::DATA_TYPE_INT:
                $lengthFormat = [
                    1 => 'C',
                    2 => 'n',
                    4 => 'N',
                ];
                return pack("$lengthFormat[$length]", substr($value, 0, $length));
                break;
            case self::DATA_TYPE_VARCHAR:
                return $value . chr(0);
                break;
            case self::DATA_TYPE_CHAR:
                if ($value !== null) {
                    return chr($value);
                }
                break;
            case self::DATA_TYPE_DATETIME:
                // @TODO Add encode support for datetime type
                break;
        }
        return chr(0);
    }

    /**
     * Decode packet data and populate PDU properties.
     *
     * @param string $data
     *
     * @return $this
     *
     * @throws PduException
     */
    public function decode($data)
    {
        $offset = 0;
        $packetFormat = array_merge($this->packetHeaderFormat, $this->packetBodyFormat);
        foreach ($packetFormat as $section) {
            $length = array_key_exists('length', $section) ? $section['length'] : null;
            $this->decodeSection($data, $section['type'], $section['field'], $length, $offset);
        }
        return $this;
    }

    /**
     * Decode a particular section of the packet data.
     *
     * @param string   $data
     * @param string   $type
     * @param string   $field
     * @param int|null $length
     * @param int      $offset
     *
     * @throws PduException
     */
    public function decodeSection($data, $type, $field, $length, &$offset)
    {
        switch ($type)
        {
            case self::DATA_TYPE_INT:
                $value = true;
                if ($length > 0) {
                    $value = $this->decodeInt($data, $offset, $length);
                }
                break;
            case self::DATA_TYPE_VARCHAR:
                $value = $this->decodeVarchar($data, $offset, $length);
                break;
            case self::DATA_TYPE_CHAR:
                $value = ord($data[$offset]);
                $offset++;
                break;
            case self::DATA_TYPE_DATETIME:
                // @TODO Add decode support for datetime type
                break;
            default:
                throw new PduException('Cannot decode unknown type "'.$type.'"');
        }
        $this->set($field, $value);
    }

    /**
     * Decode an integer part of the packet data.
     *
     * @param string $data
     * @param int    $offset
     * @param int    $length
     *
     * @return int
     */
    protected function decodeInt($data, &$offset, $length)
    {
        $format = "N";
        switch ($length)
        {
            case 1:
                $format = 'C';
                break;
            case 2:
                $format = 'n';
                break;
            case 8:
                $format = 'J';
                break;
        }
        $int = null;
        extract(unpack("{$format}int", substr($data, $offset, $length)), EXTR_OVERWRITE);
        $offset += $length;
        return $int & 0xFFFFFFFF;
    }

    protected function decodeVarchar($data, &$offset, &$length)
    {
        if ("\0" === $data[$offset]) {
            $offset++;
            return null;
        }

        $remainingSize = strlen($data)-$offset;
        if ($length > $remainingSize) {
            $length = $remainingSize;
        }

        $string = '';
        $current = $data[$offset];
        while("\0" !== $current && $offset < ($offset+$length)) {
            $string.= $current;
            $offset++;
            if (isset($data[$offset])) {
                $current = $data[$offset];
            }
        }
        $offset++;
        if ("\0" !== $current) {
            throw new MalformedPduException('Expected terminating null at offset ' . $offset);
        }
        return $string;
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
     * @return bool
     */
    public function isResponse()
    {
        return $isResponse;
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
