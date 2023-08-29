<?php

declare(strict_types=1);

namespace IntegerNet\DumpServer\Console\Command;

use IntegerNet\DumpServer\Plugin\SetServerAddress;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Command\Descriptor\CliDescriptor;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Server\DumpServer as SymfonyDumpServer;
use Webmozart\Assert\Assert;

class DumpServer extends Command
{
    private const OPTION_HOST = '--host';

    protected function configure(): void
    {
        $this->setName('server:dump')
            ->setAliases(['dump-server'])
            ->setDescription('Starts a dump server for the symfony var-dumper component')
            ->addOption(
                self::OPTION_HOST,
                null,
                InputOption::VALUE_REQUIRED,
                'The address the server should listen to',
                SetServerAddress::DEFAULT_ADDRESS
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $errorOutput = $output instanceof ConsoleOutputInterface
            ? $output->getErrorOutput()
            : $output;

        $host = $input->getParameterOption(self::OPTION_HOST, SetServerAddress::DEFAULT_ADDRESS, true);
        Assert::string($host);

        $logger = new ConsoleLogger($errorOutput);
        $server = new SymfonyDumpServer($host, $logger);
        $io     = new SymfonyStyle($input, $output);

        $errorIo = $io->getErrorStyle();
        $errorIo->title('Symfony Var Dumper Server');

        $server->start();

        $errorIo->success(sprintf('Server listening on %s', $server->getHost()));
        $errorIo->comment('Quit the server with CONTROL-C.');

        $descriptor = new CliDescriptor(new CliDumper());
        $server->listen(function (Data $data, array $context, int $clientId) use ($descriptor, $io) {
            $descriptor->describe($io, $data, $context, $clientId);
        });

        return self::SUCCESS;
    }
}
