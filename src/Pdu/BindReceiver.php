<?php

namespace Choccybiccy\Smpp\Pdu;

/**
 * Class BindReceiver.
 */
class BindReceiver extends AbstractPdu
{
    /**
     * @var string
     */
    protected $systemId;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $systemType;

    /**
     * @var int
     */
    protected $interfaceVersion;

    /**
     * @var int
     */
    protected $addrTon;

    /**
     * @var int
     */
    protected $addrNpi;

    /**
     * @var string
     */
    protected $addressRange;

    /**
     * @var array
     */
    protected $packetBodyFormat = [
        [
            'field' => 'systemId',
            'type' => self::DATA_TYPE_VARCHAR,
            'length' => 16,
        ],
        [
            'field' => 'password',
            'type' => self::DATA_TYPE_VARCHAR,
            'length' => 9,
        ],
        [
            'field' => 'systemType',
            'type' => self::DATA_TYPE_VARCHAR,
            'length' => 13,
        ],
        [
            'field' => 'interfaceVersion',
            'type' => self::DATA_TYPE_CHAR,
        ],
        [
            'field' => 'addrTon',
            'type' => self::DATA_TYPE_CHAR,
        ],
        [
            'field' => 'addrNpi',
            'type' => self::DATA_TYPE_CHAR,
        ],
        [
            'field' => 'addressRange',
            'type' => self::DATA_TYPE_VARCHAR,
            'length' => 41,
        ],
    ];

    /**
     * @return string
     */
    public function getSystemId()
    {
        return $this->systemId;
    }

    /**
     * @param string $systemId
     *
     * @return BindReceiver
     */
    public function setSystemId($systemId)
    {
        $this->systemId = $systemId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return BindReceiver
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getSystemType()
    {
        return $this->systemType;
    }

    /**
     * @param string $systemType
     *
     * @return BindReceiver
     */
    public function setSystemType($systemType)
    {
        $this->systemType = $systemType;
        return $this;
    }

    /**
     * @return int
     */
    public function getInterfaceVersion()
    {
        return $this->interfaceVersion;
    }

    /**
     * @param int $interfaceVersion
     *
     * @return BindReceiver
     */
    public function setInterfaceVersion($interfaceVersion)
    {
        $this->interfaceVersion = $interfaceVersion;
        return $this;
    }

    /**
     * @return int
     */
    public function getAddrTon()
    {
        return $this->addrTon;
    }

    /**
     * @param int $addrTon
     *
     * @return BindReceiver
     */
    public function setAddrTon($addrTon)
    {
        $this->addrTon = $addrTon;
        return $this;
    }

    /**
     * @return int
     */
    public function getAddrNpi()
    {
        return $this->addrNpi;
    }

    /**
     * @param int $addrNpi
     *
     * @return BindReceiver
     */
    public function setAddrNpi($addrNpi)
    {
        $this->addrNpi = $addrNpi;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddressRange()
    {
        return $this->addressRange;
    }

    /**
     * @param string $addressRange
     *
     * @return BindReceiver
     */
    public function setAddressRange($addressRange)
    {
        $this->addressRange = $addressRange;
        return $this;
    }

    /**
     * @return array
     */
    public function getPacketBodyFormat()
    {
        return $this->packetBodyFormat;
    }

    /**
     * @param array $packetBodyFormat
     *
     * @return BindReceiver
     */
    public function setPacketBodyFormat($packetBodyFormat)
    {
        $this->packetBodyFormat = $packetBodyFormat;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCommandId()
    {
        return 0x00000001;
    }

    /**
     * @inheritDoc
     */
    public function getCommandName()
    {
        return 'bind_receiver';
    }
}
