# php-terminal-app

How to execute:

composer install
./bin/app

Notes:
I know this is a small app but I still prefer the symfony component in case we want to expand later.

Change in specification:
 - Original input: your-program * * * * * /usr/bin/php --version
 - New input format: your-program parseCron "* * * * * /usr/bin/php --version"

Made sure to deal with @ words.