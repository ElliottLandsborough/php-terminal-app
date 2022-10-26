# PHP Terminal App

https://github.com/ElliottLandsborough/php-terminal-app

[![CircleCI](https://circleci.com/gh/ElliottLandsborough/php-terminal-app.svg?style=svg)](https://circleci.com/gh/ElliottLandsborough/php-terminal-app)
[![Code Style](https://github.styleci.io/repos/557697023/shield?style=flat&branch=main)](https://github.styleci.io/repos/557697023)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/83b45b1fc606435ab44e5d5e757b6af6)](https://www.codacy.com/gh/ElliottLandsborough/php-terminal-app/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ElliottLandsborough/php-terminal-app&amp;utm_campaign=Badge_Grade)

## How to install

```bash
composer install
./bin/app
```

## How to test
```bash
composer install
./vendor/bin/phpunit
```

## How to test with docker
```bash
docker-compose up
```

## Notes

I know this is a small app but I still prefer the symfony component in case we want to expand later. It works with all @ nicknames other than '@restart'. Equivalents were taken from the crontab manpage.

### Original input based on specification

```bash
./path-to-app * * * * * /usr/bin/php --version
```

### New input format adds the type of command and quotes around the cron line
```bash
./bin/app parseCron "* * * * * /usr/bin/php --version"
```

### Input
```bash
./bin/app parseCron "*/10 1,2,3 5-11 1 6 /usr/bin/php --version"
```

### Output
```bash
+--------------+------------------------+
| Minute       | 0 10 20 30 40 50       |
| Hour         | 1 2 3                  |
| Day of month | 5 6 7 8 9 10 11        |
| Month        | 1                      |
| Day of week  | 6                      |
| Command      | /usr/bin/php --version |
+--------------+------------------------+
```