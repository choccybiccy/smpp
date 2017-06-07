<?php

namespace Choccybiccy\Smpp;

use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\Connector;
use React\Socket\TcpServer;
use Symfony\Component\Console\Input\InputArgument;

class SmscApplication extends AbstractApplication
{
    /**
     * @var array
     */
    protected $inputArguments = [
        ['listen', InputArgument::OPTIONAL, 'Listen address', '0.0.0.0:9000'],
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
     */
    protected function execute()
    {
        $server = new TcpServer($this->input->getArgument('listen'), $this->loop);
        $this->log->info('Server started, waiting for connections');
    }
}
