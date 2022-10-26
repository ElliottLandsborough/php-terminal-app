<?php

// tests/Util/BreedUtilTest.php

namespace ElliottLandsborough\PhpTerminalApp\Tests\Services;

use ElliottLandsborough\PhpTerminalApp\Commands\ParseCron;
use Exception;
use PHPUnit\Framework\TestCase;

class ParseCronTest extends TestCase
{
    protected $cron;

    // runs per test
    public function setUp(): void
    {
        $this->cron = new Cron();
    }

    public function testCommand()
    {
    }
}
