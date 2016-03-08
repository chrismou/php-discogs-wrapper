# PHP Discogs API Wrapper

[![Build Status](https://travis-ci.org/chrismou/php-discogs-wrapper.svg?branch=master)](https://travis-ci.org/chrismou/php-discogs-wrapper)
[![Test Coverage](https://codeclimate.com/github/chrismou/php-discogs-wrapper/badges/coverage.svg)](https://codeclimate.com/github/chrismou/php-discogs-wrapper/coverage)
[![Code Climate](https://codeclimate.com/github/chrismou/php-discogs-wrapper/badges/gpa.svg)](https://codeclimate.com/github/chrismou/php-discogs-wrapper)

A dead simple wrapper class for the discogs API.

This is an early days version. I'd probably advise not using it just yet, as methods are liable to change without notice
until I'm happy enough with it to tag a version.


## Tests

To run the unit test suite:

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
./vendor/bin/phpunit
```

If you use docker, you can also run the test suite against all supported PHP versions:
```
./vendor/bin/dunit
```

## License

Released under the MIT License. See [LICENSE](LICENSE.md).
