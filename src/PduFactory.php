<?php

namespace Choccybiccy\Smpp;

use Choccybiccy\Smpp\Pdu\AbstractPdu;
use Choccybiccy\Smpp\Pdu\BindReceiver;
use Choccybiccy\Smpp\Pdu\BindReceiverResp;
use Choccybiccy\Smpp\Pdu\BindTransceiver;
use Choccybiccy\Smpp\Pdu\BindTransceiverResp;
use Choccybiccy\Smpp\Pdu\BindTransmitter;
use Choccybiccy\Smpp\Pdu\BindTransmitterResp;
use Choccybiccy\Smpp\Pdu\CancelSm;
use Choccybiccy\Smpp\Pdu\CancelSmResp;
use Choccybiccy\Smpp\Pdu\DeliverSm;
use Choccybiccy\Smpp\Pdu\DeliverSmResp;
use Choccybiccy\Smpp\Pdu\EnquireLink;
use Choccybiccy\Smpp\Pdu\EnquireLinkResp;
use Choccybiccy\Smpp\Pdu\Exception\UnsupportedPduCommandException;
use Choccybiccy\Smpp\Pdu\GenericNack;
use Choccybiccy\Smpp\Pdu\QuerySm;
use Choccybiccy\Smpp\Pdu\QuerySmResp;
use Choccybiccy\Smpp\Pdu\ReplaceSm;
use Choccybiccy\Smpp\Pdu\ReplaceSmResp;
use Choccybiccy\Smpp\Pdu\SubmitSm;
use Choccybiccy\Smpp\Pdu\SubmitSmResp;
use Choccybiccy\Smpp\Pdu\Unbind;
use Choccybiccy\Smpp\Pdu\UnbindResp;

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
        'bind_receiver' => BindReceiver::class,
        'bind_receiver_resp' => BindReceiverResp::class,
        'bind_transceiver' => BindTransceiver::class,
        'bind_transceiver_resp' => BindTransceiverResp::class,
        'bind_transmitter' => BindTransmitter::class,
        'bind_transmitter_resp' => BindTransmitterResp::class,
        'cancel_sm' => CancelSm::class,
        'cancel_sm_resp' => CancelSmResp::class,
        'deliver_sm' => DeliverSm::class,
        'deliver_sm_resp' => DeliverSmResp::class,
        'enquire_link' => EnquireLink::class,
        'enquire_link_resp' => EnquireLinkResp::class,
        'generick_nack' => GenericNack::class,
        'query_sm' => QuerySm::class,
        'query_sm_resp' => QuerySmResp::class,
        'replace_sm' => ReplaceSm::class,
        'replace_sm_resp' => ReplaceSmResp::class,
        'submit_sm' => SubmitSm::class,
        'submit_sm_resp' => SubmitSmResp::class,
        'unbind' => Unbind::class,
        'unbind_resp' => UnbindResp::class,
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
        if ($packetLength < AbstractPdu::HEADER_SIZE) {
            return null;
        }
        $position = 0;
        while ($position < $packetLength) {
            /** @var int $pduLength */
            extract(unpack('NpduLength', substr($data, $position, 4)), EXTR_OVERWRITE);
            $pduData = substr($data, $position, $pduLength);
            /** @var int $commandId */
            extract(unpack('NcommandId', substr($pduData, 4, 4)));
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
     * @return AbstractPdu
     *
     * @throws UnsupportedPduCommandException
     */
    public function createFromCommand($command, array $parameters = null)
    {
        if (!array_key_exists($command, $this->commands)) {
            throw new UnsupportedPduCommandException("The command {$command} is not supported");
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
     * @return AbstractPdu
     *
     * @throws UnsupportedPduCommandException
     */
    public function createFromCommandId($id, array $parameters = null)
    {
        if (!array_key_exists($id, $this->commandIds)) {
            throw new UnsupportedPduCommandException("The command ID {$id} is not supported");
        }
        return $this->createFromCommand($this->commandIds[$id], $parameters);
    }
}
