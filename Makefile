PHP = /usr/local/opt/php@8.2/bin/php
COMPOSER = /usr/local/opt/composer/bin/composer

up:
	$(PHP) $(COMPOSER) up

test:
	$(PHP) vendor/bin/phpunit

phpstan:
	$(PHP) vendor/bin/phpstan