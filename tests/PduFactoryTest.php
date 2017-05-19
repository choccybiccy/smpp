<?php

namespace Choccybiccy\Smpp;

use Choccybiccy\Smpp\Pdu\BindTransceiver;
use PHPUnit\Framework\TestCase;

class PduFactoryTest extends TestCase
{
    /**
     * @param array|null $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|PduFactory
     */
    protected function getMockPduFactory($methods = null)
    {
        return $this->getMockBuilder(PduFactory::class)
            ->setMethods($methods)
            ->getMock();
    }

    public function testCreateFromData()
    {
        $packet = hex2bin('0000001d0000000900000000000000c861716c0061716c000034000000');
        $factory = $this->getMockPduFactory();
        $pdus = $factory->createFromData($packet);
        $this->assertCount(1, $pdus);
        /** @var BindTransceiver $bind */
        $bind = $pdus[0];
        $this->assertInstanceOf(BindTransceiver::class, $bind);

        var_dump(bin2hex($bind->encode()));die();
    }
}
