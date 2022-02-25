.PHONY: install
install:
	composer install

.PHONY: quality
quality:
	vendor/bin/php-cs-fixer fix --ansi --dry-run --using-cache=no --verbose

.PHONY: quality-fix
quality-fix:
	vendor/bin/php-cs-fixer fix

.PHONY: test
test:
	vendor/bin/atoum
