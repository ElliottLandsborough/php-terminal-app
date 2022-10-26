# php-terminal-app

[![Code Style](https://github.styleci.io/repos/557697023/shield?style=flat&branch=master)](https://github.styleci.io/repos/557697023)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/83b45b1fc606435ab44e5d5e757b6af6)](https://www.codacy.com/gh/ElliottLandsborough/php-terminal-app/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ElliottLandsborough/php-terminal-app&amp;utm_campaign=Badge_Grade)

How to execute:

composer install
./bin/app

Notes:
I know this is a small app but I still prefer the symfony component in case we want to expand later.

Change in specification:
   - Original input: your-program * * * * * /usr/bin/php --version
   - New input format: your-program parseCron "* * * * * /usr/bin/php --version"

Made sure to deal with @ words.