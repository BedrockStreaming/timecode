.PHONY: test lint

all: lint test

lint:
	vendor/bin/coke

test:
	vendor/bin/atoum
