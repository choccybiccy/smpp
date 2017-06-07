<?php

namespace Choccybiccy\Smpp;

use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Socket\TcpServer;
use Symfony\Component\Console\Input\InputArgument;

class SmscApplication extends AbstractApplication
{
    /**
     * @var array
     */
    protected $inputArguments = [
        ['listen', InputArgument::OPTIONAL, 'Listen address'],
    ];

    /**
     * EsmeApplication constructor.
     *
     * @param LoopInterface   $loop
     * @param LoggerInterface $log
     */
    public function __construct(LoopInterface $loop, LoggerInterface $log)
    {
        parent::__construct($loop, $log);
    }

    /**
     * @inheritDoc
     *
     * @throws \RuntimeException
     */
    protected function execute()
    {
        $listen = $this->input->getArgument('listen') ?: $this->config->get('smsc.listen_address');
        if (!$listen) {
            throw new \RuntimeException('No listen address specified');
        }
        $server = new TcpServer($listen, $this->loop);
        $this->log->info('Listening for connections on ' . $server->getAddress());
    }
}
