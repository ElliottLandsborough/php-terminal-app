<?php

namespace ElliottLandsborough\PhpTerminalApp\Commands;

use ElliottLandsborough\PhpTerminalApp\Services\Cron;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'parseCron', description: 'Parse a cron line')]
class ParseCron extends Command
{
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            // ...
            ->addArgument('cron_string', InputArgument::REQUIRED, 'A line from crontab e.g * * * * * /usr/bin/php --version');
    }

    /**
     * Execute the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int 0 if everything went fine, or an exit code.
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $cron = new Cron();

        $array = $cron->parseToArray($input->getArgument('cron_string'));

        $rows = [];

        foreach ($array as $title => $array) {
            $rows[] = [$title, implode(' ', $array)];
        }

        $table = new Table($output);
        $table->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
