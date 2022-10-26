# php-terminal-app

How to execute:

composer install
./bin/app

Notes:
I know this is a small app but I still prefer the symfony component in case we want to expand later.
Change in specification:
 - Original input: your-program 00 09-18 * * 1-5 /usr/bin/echo "hello world"
 - New input format: your-program parseCron 00 09-18 * * 1-5 /usr/bin/echo "hello world"