<?php

namespace ElliottLandsborough\PhpTerminalApp\Services;

class Cron {
    public function parseToArray(String $input): array
    {
        return explode(" ", $input);
    }
}