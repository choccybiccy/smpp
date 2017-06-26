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
    protected $type;

    /**
     * @var string
     */
    protected $systemId;

    /**
     * @var string
     */
    protected $password;

    /**
     * Connection constructor.
     * @param string $address
     * @param string $type
     * @param string $systemId
     * @param string $password
     */
    public function __construct($address, $type, $systemId, $password)
    {
        $this->address = $address;
        $this->type = $type;
        $this->systemId = $systemId;
        $this->password = $password;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getSystemId()
    {
        return $this->systemId;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
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
                $connection['type'],
                $connection['systemId'],
                $connection['password']
            );
        }
        return $return;
    }
}
