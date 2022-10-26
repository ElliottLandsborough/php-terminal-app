# PHP Terminal App

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

I know this is a small app but I still prefer the symfony component in case we want to expand later. It works with all @ aliases other than '@restart'.

### Original input based on specification

```
./your-program * * * * * /usr/bin/php --version
```

### New input format adds the type of command and quotes around the cron line
```
./your-program parseCron "* * * * * /usr/bin/php --version"
```