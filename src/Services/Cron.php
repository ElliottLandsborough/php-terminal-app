<?php

namespace ElliottLandsborough\PhpTerminalApp\Services;

use Exception;

class Cron
{
    /**
     * Validate a cron time string e.g '* * * * *'.
     *
     * @param string $input The cron time string to be validated
     *
     * @return void
     */
    protected function validate(string $input)
    {
        // https://stackoverflow.com/questions/14203122/create-a-regular-expression-for-cron-statement
        $regex = '/^((((\d+,)+\d+|(\d+(\/|-|#)\d+)|\d+L?|\*(\/\d+)?|L(-\d+)?|\?|[A-Z]{3}(-[A-Z]{3})?) ?){5,7})$/m';

        if (!preg_match($regex, trim($input))) {
            throw new Exception('Invalid cron string: '.$input);
        }
    }

    /**
     * Take crontab input and replace any special strings with proper time strings.
     *
     * @param string $input A cron line
     *
     * @return string The string with the special value replaced
     */
    protected function parseNicknames(string $input): string
    {
        // List of 'special' cron strings
        $nickNames = [
            '@reboot'   => false,
            '@yearly'   => '0 0 1 1 *,',
            '@anually'  => '0 0 1 1 *,',
            '@monthly'  => '0 0 1 * *',
            '@weekly'   => '0 0 * * 0',
            '@daily'    => '0 0 * * *',
            '@hourly'   => '0 * * * *',
        ];

        foreach ($nickNames as $original => $replacement) {
            if (str_starts_with($input, $original)) {
                if ($replacement === false) {
                    throw new Exception('Time machine not found.');
                }

                $input = str_replace($original, $replacement, $input);

                break;
            }
        }

        return $input;
    }

    /**
     * Generate/parse the time values from the cron time string.
     *
     * @param string $input The cron time string e.g '* * * * *'
     * @param int    $min   Minimum for the specific timeset e.g '1' for months
     * @param int    $max   Maximum for the specifix timeset e.g '12' for months
     *
     * @return array<int>
     */
    protected function parseTimeValues(string $input, int $min, int $max): array
    {
        $result = [];

        // commas represent multiple ranges
        $commaSections = explode(',', $input);

        // loop through each range
        foreach ($commaSections as $commaSection) {
            // forward slash represents a divisor
            $divisor = explode('/', $commaSection);

            // If we have no divisor, we are stepping up one at a time
            // else step upwards by divisor
            $step = empty($divisor[1]) ? 1 : $divisor[1];

            // Dash represents a range of values e.g 1-5 = 1,2,3,4,5
            $dashArray = explode('-', $divisor[0]);

            // We want the top half of the divisor fraction
            $minimum = $divisor[0];

            // If the item was '*' then use the provided minimum
            if ($divisor[0] == '*') {
                $minimum = $min;
            }

            // Two items in dash array, so minimum is first item
            if (count($dashArray) == 2) {
                $minimum = $dashArray[0];
            }

            // we want the top half of the divisor fraction
            $maximum = $divisor[0];

            // If the item was '*' then use the provided minimum
            if ($divisor[0] == '*') {
                $maximum = $max;
            }

            // Two items in dash array, so minimum is second item
            if (count($dashArray) == 2) {
                $maximum = $dashArray[1];
            }

            if ($minimum < $min) {
                $error = "Invalid: $input. '$minimum' too low. Minimum: '$min'.";

                throw new Exception($error);
            }

            if ($maximum > $max) {
                $error = "Invalid: $input. '$maximum' too high. Maximum: '$max'.";

                throw new Exception();
            }

            // Step through from minimum to maximum
            for ($i = $minimum; $i <= $maximum; $i += $step) {
                $result[$i] = (int) $i;
            }
        }

        sort($result);

        return $result;
    }

    /**
     * Parse cron string to an array of dates and commands.
     *
     * @param string $input A full cron string e.g '* * * * * /usr/bin/php --version'
     *
     * @return array<string, array<int|string>>
     */
    public function parseToArray(string $input): array
    {
        $input = trim($input);

        $input = $this->parseNicknames($input);

        $exploded = explode(' ', trim($input));

        $timeArray = array_slice($exploded, 0, 5);
        $timeString = implode(' ', $timeArray);

        $commandArray = array_slice($exploded, 5);

        $this->validate($timeString);

        return [
            'Minute'        => $this->parseTimeValues($timeArray[0], 0, 59),
            'Hour'          => $this->parseTimeValues($timeArray[1], 0, 23),
            'Day of month'  => $this->parseTimeValues($timeArray[2], 1, 31),
            'Month'         => $this->parseTimeValues($timeArray[3], 1, 12),
            'Day of week'   => $this->parseTimeValues($timeArray[4], 0, 6),
            'Command'       => $commandArray,
        ];
    }
}
