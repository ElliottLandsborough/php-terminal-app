<?php

namespace ElliottLandsborough\PhpCronParse\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use ElliottLandsborough\PhpCronParse\Services\Cron;

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

    /**
     * Execute the command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int 0 if everything went fine, or an exit code.
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
        Cron $cron
    ): int {
        /*
        $term1 = rand(1, 10);
        $term2 = rand(1, 10);
        $result = $term1 + $term2;

        $io = new SymfonyStyle($input, $output);

        $answer = (int) $io->ask(sprintf('What is %s + %s?', $term1, $term2));

        if ($answer === $result) {
            $io->success('Well done!');
        } else {
            $io->error(sprintf('Aww, so close. The answer was %s', $result));
        }

        if ($io->confirm('Play again?')) {
            return $this->execute($input, $output);
        }

        */



        return Command::SUCCESS;
    }
}