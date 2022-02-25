.PHONY: install
install:
	composer install

.PHONY: quality-fix
quality-fix:
	vendor/bin/php-cs-fixer fix

.PHONY: lint
lint:
	vendor/bin/php-cs-fixer fix --ansi --dry-run --using-cache=no --verbose

.PHONY: test
test:
	vendor/bin/atoum
