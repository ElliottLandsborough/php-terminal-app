<?php

namespace ElliottLandsborough\PhpTerminalApp\Services;

use Exception;
use Throwable;

class Cron {
    protected function validate(String $input) {
        // https://github.com/jkonieczny/PHP-Crontab/blob/da2d961f859412a107d8affd6acff751138ed5ee/Crontab.class.php#L54
        $regex = '/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i';

        if(!preg_match($regex, trim($input))){
            throw new Exception("Invalid cron string: ".$input);
        }
    }

    protected function parseSpecialStrings(String $input): string {
        // List of 'special' cron strings
        $specialStrings = [
            '@reboot'   => false,
            '@yearly'   => '0 0 1 1 *,',
            '@anually'  => '0 0 1 1 *,',
            '@monthly'  => '0 0 1 * *',
            '@weekly'   => '0 0 * * 0',
            '@daily'    => '0 0 * * *',
            '@midnight' => '0 0 * * *',
            '@hourly'   => '0 * * * *',
        ];

        foreach ($specialStrings as $original => $replacement) {
            if (str_starts_with($input, $original)) {
                if ($replacement === false) {
                    throw new Exception("Time machine not found. Cannot tell when next reboot will occur.");
                }

                $input = str_replace($original, $replacement, $input);

                break;
            }
        }

        return $input;
    }

    public function parseTimeValues(String $s, int $min, int $max): array
    {
        $result = [];
    
        // commas represent multiple ranges
        $commaSections = explode(',', $s);

        // loop through each range
        foreach ($commaSections as $commaSection) {
            // forward slash represents a divisor
            $divisor = explode('/', $commaSection);

            // If we have no divisor, we are stepping up one at a time
            // else step upwards by divisor
            $step = empty($divisor[1]) ? 1 : $divisor[1];

            // Dash represents a range of values e.g 1-5 = 1,2,3,4,5
            $dashArray = explode('-', $divisor[0]);

            // Two items in dash array, so minimum is first item
            if (count($dashArray) == 2) {
                $minimum = $dashArray[0];
            } else {
                // If the item was '*' then use the provided minimum
                if ($divisor[0] == '*') {
                    $minimum = $min;
                }
                else {
                    // Otherwise we want the top half of the divisor fraction
                    $minimum = $divisor[0];
                }
            }

            //$_max = count($vvvv)==2?$vvvv[1]:($vvv[0]=='*'?$max:$vvv[0]);

            // Two items in dash array, so minimum is second item
            if (count($dashArray) == 2) {
                $maximum = $dashArray[1];
            } else {
                // If the item was '*' then use the provided minimum
                if ($divisor[0] == '*') {
                    $maximum = $max;
                }
                else {
                    // Otherwise we want the top half of the divisor fraction
                    $maximum = $divisor[0];
                }
            }

            // Step through from minimum to maximum
            for($i = $minimum; $i <= $maximum; $i += $step) {
                $result[$i]=intval($i);
            }
        }

        ksort($result);

        return $result;
    }

    public function parseToArray(String $input): array
    {
        $input = trim($input);

        $input = $this->parseSpecialStrings($input);

        $exploded = explode(" ", trim($input));

        $timeArray = array_slice($exploded, 0, 5);
        $timeString = implode(' ', $timeArray);

        $commandArray = array_slice($exploded, 5);

        $this->validate($timeString);

        return [
            'Minute'    => $this->parseTimeValues($timeArray[0],0,59),
            'Hour'      => $this->parseTimeValues($timeArray[1],0,23),
            'Day of month'  => $this->parseTimeValues($timeArray[2],1,31),
            'Month'     => $this->parseTimeValues($timeArray[3],1,12),
            'Day of week'   => $this->parseTimeValues($timeArray[4],0,6),
            'Command'   => $commandArray,
        ];
    }
}