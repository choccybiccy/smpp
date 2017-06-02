<?php

namespace Choccybiccy\Smpp;

use Choccybiccy\Smpp\Pdu;

/**
 * Class PduFactory.
 */
class PduFactory
{
    /**
     * Command names to classes.
     * @var array
     */
    protected $commands = [
        'bind_receiver'         => Pdu\BindReceiver::class,
        'bind_receiver_resp'    => Pdu\BindReceiverResp::class,
        'bind_transceiver'      => Pdu\BindTransceiver::class,
        'bind_transceiver_resp' => Pdu\BindTransceiverResp::class,
        'bind_transmitter'      => Pdu\BindTransmitter::class,
        'bind_transmitter_resp' => Pdu\BindTransmitterResp::class,
        'cancel_sm'             => Pdu\CancelSm::class,
        'cancel_sm_resp'        => Pdu\CancelSmResp::class,
        'deliver_sm'            => Pdu\DeliverSm::class,
        'deliver_sm_resp'       => Pdu\DeliverSmResp::class,
        'enquire_link'          => Pdu\EnquireLink::class,
        'enquire_link_resp'     => Pdu\EnquireLinkResp::class,
        'generick_nack'         => Pdu\GenericNack::class,
        'query_sm'              => Pdu\QuerySm::class,
        'query_sm_resp'         => Pdu\QuerySmResp::class,
        'replace_sm'            => Pdu\ReplaceSm::class,
        'replace_sm_resp'       => Pdu\ReplaceSmResp::class,
        'submit_sm'             => Pdu\SubmitSm::class,
        'submit_sm_resp'        => Pdu\SubmitSmResp::class,
        'unbind'                => Pdu\Unbind::class,
        'unbind_resp'           => Pdu\UnbindResp::class,
    ];

    /**
     * Command IDs to names.
     * @var array
     */
    protected $commandIds = [
        0x00000001 => 'bind_receiver',
        0x80000001 => 'bind_receiver_resp',
        0x00000009 => 'bind_transceiver',
        0x80000009 => 'bind_transceiver_resp',
        0x00000002 => 'bind_transmitter',
        0x80000002 => 'bind_transmitter_resp',
        0x00000008 => 'cancel_sm',
        0x80000008 => 'cancel_sm_resp',
        0x00000005 => 'deliver_sm',
        0x80000005 => 'deliver_sm_resp',
        0x00000015 => 'enquire_link',
        0x80000015 => 'enquire_link_resp',
        0x80000000 => 'generic_nack',
        0x00000003 => 'query_sm',
        0x80000003 => 'query_sm_resp',
        0x00000007 => 'replace_sm',
        0x80000007 => 'replace_sm_resp',
        0x00000004 => 'submit_sm',
        0x80000004 => 'submit_sm_resp',
        0x00000006 => 'unbind',
        0x80000006 => 'unbind_resp',
    ];

    /**
     * @param string $data
     *
     * @return Pdu\AbstractPdu[]|null
     */
    public function createFromData($data)
    {
        $pdus = [];
        $packetLength = strlen($data);
        if ($packetLength < Pdu\AbstractPdu::HEADER_SIZE) {
            return null;
        }
        $position = 0;
        while ($position < $packetLength) {
            /** @var int $pduLength */
            extract(unpack('NpduLength', substr($data, $position, 4)), EXTR_OVERWRITE);
            $pduData = substr($data, $position, $pduLength);
            /** @var int $commandId */
            extract(unpack('NcommandId', substr($pduData, 4, 4)), EXTR_OVERWRITE);
            if (array_key_exists($commandId, $this->commandIds)) {
                $pdus[] = $this->createFromCommandId($commandId)->decode($data);
            }
            $position += $pduLength;
        }
        return $pdus;
    }

    /**
     * Create PDU from command.
     *
     * @param string     $command
     * @param array|null $parameters
     *
     * @return Pdu\AbstractPdu
     *
     * @throws Pdu\Exception\UnsupportedPduCommandException
     */
    public function createFromCommand($command, array $parameters = null)
    {
        if (!array_key_exists($command, $this->commands)) {
            throw new Pdu\Exception\UnsupportedPduCommandException("The command {$command} is not supported");
        }
        $class = $this->commands[$command];
        return new $class($parameters);
    }

    /**
     * Create PDU from command ID.
     *
     * @param int        $id
     * @param array|null $parameters
     *
     * @return Pdu\AbstractPdu
     *
     * @throws Pdu\Exception\UnsupportedPduCommandException
     */
    public function createFromCommandId($id, array $parameters = null)
    {
        if (!array_key_exists($id, $this->commandIds)) {
            throw new Pdu\Exception\UnsupportedPduCommandException("The command ID {$id} is not supported");
        }
        return $this->createFromCommand($this->commandIds[$id], $parameters);
    }
}
