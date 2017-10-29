default: help

help:
	@echo "Usage:"
	@echo "     make [command]"
	@echo "Available commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^_' | sed 's/://' | xargs -n 1 echo ' -'

coverage:
	rm -rf coverage; bin/phpunit --coverage-html=coverage/ --coverage-clover=coverage/clover.xml

unit-tests:
	bin/phpunit --testsuite unit

integration-tests:
	bin/phpunit --testsuite integration

cs-fix:
	./bin/php-cs-fixer fix --verbose
