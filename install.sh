#!/bin/sh

curl -s getcomposer.org/installer | php -d detect_unicode=Off -d date.timezone=UTC

./composer.phar install

printf '\n \033[0;32m%s\033[0m\n' '.... scaffold.php is now ready.'