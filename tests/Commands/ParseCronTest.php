<?php

// tests/Util/BreedUtilTest.php

namespace ElliottLandsborough\PhpTerminalApp\Tests\Services;

use ElliottLandsborough\PhpTerminalApp\Commands\ParseCron;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ParseCronTest extends TestCase
{
    protected $commandTester;

    protected function setUp(): void
    {
        $application = new Application();
        $application->add(new ParseCron());
        $command = $application->find('parseCron');
        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown(): void
    {
        $this->commandTester = null;
    }

    public function testBadString()
    {
        $this->expectException(Exception::class);
        $this->commandTester->execute(['cron_string' => 'bad string']);
    }

    public function testCommand()
    {
        $this->commandTester->execute(
            [
                'cron_string' => '*/30 0,1,2,3 1-2 2 3-4 /path/executable --argument',
            ]
        );

        $output = trim($this->commandTester->getDisplay());

        $expected = <<<'EOD'
+--------------+-----------------------------+
| Minute       | 0 30                        |
| Hour         | 0 1 2 3                     |
| Day of month | 1 2                         |
| Month        | 2                           |
| Day of week  | 3 4                         |
| Command      | /path/executable --argument |
+--------------+-----------------------------+
EOD;

        $this->assertEquals($output, $expected);
    }
}
