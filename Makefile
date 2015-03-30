PHPUNIT=./vendor/bin/phpunit
PORT=8000

test: ; $(PHPUNIT) --coverage-html coverage/
server: ; php -S localhost:$(PORT) -t coverage/
trail: ; trail src && trail tests
