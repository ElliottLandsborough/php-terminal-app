<?php

namespace ElliottLandsborough\PhpTerminalApp\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use ElliottLandsborough\PhpTerminalApp\Services\Cron;

class ParseCron extends Command
{
    /**
     * The name of the command (the part after "bin/app").
     *
     * @var string
     */
    protected static $defaultName = 'parseCron';

    /**
     * The command description shown when running "php bin/app list".
     *
     * @var string
     */
    protected static $defaultDescription = 'Parse a cron line';

    protected function configure(): void
    {
        $this
            // ...
            ->addArgument('cron_string', InputArgument::REQUIRED, 'A line from crontab e.g * * * * * /usr/bin/php --version')
        ;
    }

    /**
     * Execute the command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int 0 if everything went fine, or an exit code.
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $cron = new Cron;

        print_r($cron->parseToArray($input->getArgument('cron_string')));

        return Command::SUCCESS;
    }
}