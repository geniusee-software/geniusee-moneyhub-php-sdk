check-project: test psalm check-style

psalm:
	./vendor/bin/psalm --no-cache

pi:
	./vendor/bin/phpinsights analyse src -c=/config/insights.php

test:
	./vendor/bin/phpunit

check-style:
	vendor/bin/php-cs-fixer fix --config=.php_cs_fixer.php --allow-risky=yes --using-cache=no --diff --dry-run

style-fix:
	vendor/bin/php-cs-fixer fix --config=.php_cs_fixer.php --allow-risky=yes

coverage:
	php -dxdebug.mode=coverage vendor/bin/phpunit -v --coverage-html=var/tests/coverage


