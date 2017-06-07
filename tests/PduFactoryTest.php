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
        $hex = '0000001d0000000900000000000000c861716c0061716c000034000000';
        $packet = hex2bin($hex);
        $factory = $this->getMockPduFactory();
        $pdus = $factory->createFromData($packet);
        $this->assertCount(1, $pdus);
        /** @var BindTransceiver $bind */
        $bind = $pdus[0];
        $this->assertInstanceOf(BindTransceiver::class, $bind);

        $this->assertEquals($hex, bin2hex($bind->encode()));
    }
}
