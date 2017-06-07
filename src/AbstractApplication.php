<?php

namespace Choccybiccy\Smpp;

use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractApplication
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var ArgvInput
     */
    protected $input;

    /**
     * @var array
     */
    protected $inputArguments = [];

    /**
     * @var array
     */
    protected $defaultOptions = [
        ['config', 'c', InputOption::VALUE_REQUIRED, 'Configuration file'],
        ['help', 'h', InputOption::VALUE_NONE, 'Show help'],
    ];

    /**
     * @var array
     */
    protected $inputOptions = [];

    /**
     * AbstractApplication constructor.
     *
     * @param LoopInterface   $loop
     * @param LoggerInterface $log
     */
    public function __construct(LoopInterface $loop, LoggerInterface $log)
    {
        $this->loop = $loop;
        $this->log = $log;
        $this->output = new ConsoleOutput();
        $this->inputOptions = array_merge($this->defaultOptions, $this->inputOptions);
    }

    /**
     * Load configuration from file.
     *
     * @return void
     */
    protected function loadConfiguration()
    {
        $configPath = $this->input->getOption('config') ?: __DIR__ . '/../config/config.yml';
        if (!file_exists($configPath)) {
            throw new \RuntimeException('Cannot find configuration file at ' . $configPath);
        }
        $this->config = new Config(Yaml::parse(file_get_contents($configPath)));
        $this->log->info('Configuration loaded from ' . realpath($configPath));
    }

    /**
     * Load input options.
     */
    protected function loadInput()
    {
        $options = [];
        foreach ($this->inputArguments as $argument) {
            $description = isset($argument[2]) ? $argument[2] : null;
            $default = isset($argument[3]) ? $argument[3] : null;
            $options[] = new InputArgument($argument[0], $argument[1], $description, $default);
        }
        foreach ($this->inputOptions as $option) {
            $description = isset($option[3]) ? $option[3] : null;
            $default = isset($option[4]) ? $option[4] : null;
            $options[] = new InputOption($option[0], $option[1], $option[2], $description, $default);
        }
        $this->input = new ArgvInput(null, new InputDefinition($options));
    }

    /**
     * Run the application
     */
    public function run()
    {
        try {
            if ((new ArgvInput())->getParameterOption(['--help', '-h']) !== false) {
                $this->showHelp();
            } else {
                $this->loadInput();
                $this->loadConfiguration();
                $this->execute();
                $this->loop->run();
            }
        } catch (\Exception $e) {
            $this->output->writeln(
                "<error>\n<fg=white;bg=red;options=bold>An exception has occurred!</>\n\n\t {$e->getMessage()}\n\n{$e->getTraceAsString()}</error>"
            );
        }
    }

    /**
     * Show help.
     */
    protected function showHelp()
    {
        $options = $this->inputOptions;
        $table = new Table($this->output);
        $this->output->writeln('');
        $this->output->write('Usage: ' . $_SERVER['SCRIPT_FILENAME']);
        foreach ($this->inputArguments as $argument) {
            if ($argument[1] == InputArgument::OPTIONAL) {
                $this->output->write(' [<' . $argument[0] . '>]');
            } else {
                $this->output->write(' <' . $argument[0] . '>');
            }
        }
        $this->output->write(' [');
        foreach ($options as $key => $option) {
            $this->output->write('-' . ($option[1] !== null ? $option[1] : '-' . $option[0]));
            if (in_array($option[2], [InputOption::VALUE_REQUIRED, InputOption::VALUE_OPTIONAL])) {
                $this->output->write(' <'.strtoupper($option[0]).'>');
            }
            if (($key+1) < count($options)) {
                $this->output->write(' | ');
            }
        }
        $this->output->writeln(']');
        $this->output->writeln(['', 'Options:', '']);
        $table->setStyle('compact');
        foreach ($options as $option) {
            $flags = "";
            if ($option[1] !== null) {
                $flags = '-' . $option[1] .'|';
            }
            $flags.= '--' . $option[0];
            if (in_array($option[2], [InputOption::VALUE_REQUIRED, InputOption::VALUE_OPTIONAL])) {
                $flags.= ' <'.strtoupper($option[0]).'>';
            }
            $table->addRow([
                $flags,
                '    ' . $option[3] . (array_key_exists(4, $option) ? '<comment>(default: '.$option[4].')</comment>' : ''),
            ]);
        }
        $table->render();
        $this->output->writeln('');
    }

    /**
     * @return mixed
     */
    abstract protected function execute();
}
