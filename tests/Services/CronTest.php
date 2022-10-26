<?php

// tests/Util/BreedUtilTest.php

namespace ElliottLandsborough\PhpTerminalApp\Tests\Services;

use ElliottLandsborough\PhpTerminalApp\Services\Cron;
use PHPUnit\Framework\TestCase;
use Exception;

class CronTest extends TestCase
{
    protected $cron;

    // runs per test
    public function setUp(): void
    {
        $this->cron = new Cron();
    }

    public function testParseToArray()
    {
        // test that some ranges work
        $cronLine = "0-1 0-2 1-2 1-2 0-2 /usr/bin/test";

        $response = $this->cron->parseToArray($cronLine);

        $expected = [
            'Minute' => [0, 1],
            'Hour' => [0, 1, 2],
            'Day of month' => [1, 2],
            'Month' => [1, 2],
            'Day of week' => [0, 1, 2],
            'Command' => ['/usr/bin/test'],
        ];

        $this->assertEquals($response, $expected);

        // test that every 15 minutes works
        $cronLine = "*/15 0-1 1-2 1-2 0-1 /usr/bin/test";

        $response = $this->cron->parseToArray($cronLine);

        $expected = [
            'Minute' => [0, 15, 30, 45],
            'Hour' => [0, 1],
            'Day of month' => [1, 2],
            'Month' => [1, 2],
            'Day of week' => [0, 1],
            'Command' => ['/usr/bin/test'],
        ];

        $this->assertEquals($response, $expected);

        // test that 0 hour works
        $cronLine = "0-1 0 1-2 1-2 0-1 /usr/bin/test";

        $response = $this->cron->parseToArray($cronLine);

        $expected = [
            'Minute' => [0, 1],
            'Hour' => [0],
            'Day of month' => [1, 2],
            'Month' => [1, 2],
            'Day of week' => [0, 1],
            'Command' => ['/usr/bin/test'],
        ];

        $this->assertEquals($response, $expected);

        // test that 1st/15th day of month works
        $cronLine = "0-1 0-1 1,15 1-2 0-1 /usr/bin/test";

        $response = $this->cron->parseToArray($cronLine);

        $expected = [
            'Minute' => [0, 1],
            'Hour' => [0, 1],
            'Day of month' => [1, 15],
            'Month' => [1, 2],
            'Day of week' => [0, 1],
            'Command' => ['/usr/bin/test'],
        ];

        $this->assertEquals($response, $expected);

        // test that * month works
        $cronLine = "0-1 0-1 2-3 * 0-1 /usr/bin/test";

        $response = $this->cron->parseToArray($cronLine);

        $expected = [
            'Minute' => [0, 1],
            'Hour' => [0, 1],
            'Day of month' => [2, 3],
            'Month' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Day of week' => [0, 1],
            'Command' => ['/usr/bin/test'],
        ];

        $this->assertEquals($response, $expected);

        // test that 1-5 day of month works
        $cronLine = "0-1 0-1 1-2 1-2 1-5 /usr/bin/test";

        $response = $this->cron->parseToArray($cronLine);

        $expected = [
            'Minute' => [0, 1],
            'Hour' => [0, 1],
            'Day of month' => [1, 2],
            'Month' => [1, 2],
            'Day of week' => [1, 2, 3, 4, 5],
            'Command' => ['/usr/bin/test'],
        ];

        $this->assertEquals($response, $expected);

        // test that command array works
        $cronLine = "0-1 0-1 1-2 1-2 0-1 /usr/bin/find --testme";

        $response = $this->cron->parseToArray($cronLine);

        $expected = [
            'Minute' => [0, 1],
            'Hour' => [0, 1],
            'Day of month' => [1, 2],
            'Month' => [1, 2],
            'Day of week' => [0, 1],
            'Command' => ['/usr/bin/find', '--testme'],
        ];

        $this->assertEquals($response, $expected);
    }

    public function testMonthlySpecialWord()
    {
        $cronLine = "@monthly /usr/bin/find";

        $response = $this->cron->parseToArray($cronLine);

        $expected = [
            'Minute' => [0],
            'Hour' => [0],
            'Day of month' => [1],
            'Month' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Day of week' => [0, 1, 2, 3, 4, 5, 6],
            'Command' => ['/usr/bin/find'],
        ];

        $this->assertEquals($response, $expected);
    }

    public function textRestartException()
    {
        $this->expectException(Exception::class);
        $cronLine = "@restart /usr/bin/find";
        $this->cron->parseToArray($cronLine);
    }

    public function textMinExceptionDayOfMonth()
    {
        $this->expectException(Exception::class);
        $cronLine = "0-1 0-1 0-2 1-2 0-1 /usr/bin/find";
        $this->cron->parseToArray($cronLine);
    }

    public function textMinExceptionMonth()
    {
        $this->expectException(Exception::class);
        $cronLine = "0-1 0-1 1-2 0-2 0-1 /usr/bin/find";
        $this->cron->parseToArray($cronLine);
    }

    public function textMaxException1()
    {
        $this->expectException(Exception::class);
        $cronLine = "0-999 0-1 0-2 1-2 0-1 /usr/bin/find";
        $this->cron->parseToArray($cronLine);
    }

    public function textMaxException2()
    {
        $this->expectException(Exception::class);
        $cronLine = "0-1 0-999 0-2 1-2 0-1 /usr/bin/find";
        $this->cron->parseToArray($cronLine);
    }
}
