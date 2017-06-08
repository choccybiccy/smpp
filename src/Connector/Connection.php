<?php

namespace Choccybiccy\Smpp\Connector;

class Connection
{
    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $bind;

    /**
     * @var array
     */
    protected $pdu;

    /**
     * Connection constructor.
     * @param string $address
     * @param string $bind
     * @param array $pdu
     */
    public function __construct($address, $bind, array $pdu)
    {
        $this->address = $address;
        $this->bind = $bind;
        $this->pdu = $pdu;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getBind()
    {
        return $this->bind;
    }

    /**
     * @return array
     */
    public function getPdu()
    {
        return $this->pdu;
    }

    /**
     * @param array $connections
     * @return Connection[]
     */
    public static function makeFromArray(array $connections)
    {
        $return = [];
        foreach($connections as $connection) {
            $return[] = new static(
                $connection['address'],
                $connection['bind']['type'],
                $connection['bind']['pdu']
            );
        }
        return $return;
    }
}
