# PHP Discogs API Wrapper

[![Build Status](https://travis-ci.org/chrismou/php-discogs-wrapper.svg?branch=master)](https://travis-ci.org/chrismou/php-discogs-wrapper)
[![Test Coverage](https://codeclimate.com/github/chrismou/php-discogs-wrapper/badges/coverage.svg)](https://codeclimate.com/github/chrismou/php-discogs-wrapper/coverage)
[![Code Climate](https://codeclimate.com/github/chrismou/php-discogs-wrapper/badges/gpa.svg)](https://codeclimate.com/github/chrismou/php-discogs-wrapper)
[![Buy me a beer](https://img.shields.io/badge/donate-PayPal-019CDE.svg)](https://www.paypal.me/chrismou)

A simple wrapper class for the discogs API.

#### This is an early days version. I'd probably advise not using it just yet, as methods are liable to change without notice until I'm happy enough with it to tag a version.

## Installation

Once the library is stable, it'll be added to packagist. Until then, you can access it by setting a custom repository in your composer.json.

First, add a reference to the package:

```
"require": {
    "chrismou/discogs": "dev-master"
}
```

Next, add a custom repository:

```
"repositories": [
    {
        "type": "vcs",
        "name": "chrismou/discogs",
        "url": "https://github.com/chrismou/php-discogs-wrapper"
    }
]
```

Now, running `composer update` should pull in the development package

## Usage

Currently, the library only supports usage based on a pre-acquired Discogs API access token. You can grab an access token by creating an 
Discogs account and setting up an application here: [https://www.discogs.com/settings/developers]. Once set up, click the `Generate new token'
button to generate a personal access token.

To set up the Discogs API client:

```
$discogs = new \Chrismou\Discogs\Discogs(
    new GuzzleHttp\Client(),
    YOUR_ACCESS_TOKEN,
    'YourApplicationName'
);
```

The 3rd parameter (YourApplicationName) should be something that identifies your application, and is sent along with the request as the User-Agent header. 
See the [documentation](https://www.discogs.com/developers/#page:home,header:home-general-information) for more details.

### Methods

For more information on the API response, check the Discogs documentation: [https://www.discogs.com/developers/#page:database]

If a request for a single item returns no results (Discogs returns a `404`) the client will throw a `\Chrismou\Discogs\Exception\NotFoundException` exception.

#### `$discogs->release($releaseId)`

* **$releaseId** (string) - The Discogs Release ID

#### `$discogs->masterRelease($releaseId)`

* **$masterId** (string) - The Discogs "Master" ID

#### `$discogs->masterReleaseVersions($releaseId)`

* **$masterId** (string) - The Discogs "Master" ID

#### `$discogs->artist($artistId)`

* **$artistId** (string) - The Discogs Artist ID

#### `$discogs->artistReleases($artistId)`

* **$artistId** (string) - The Discogs Artist ID

#### `$discogs->label($labelId)`

* **$labelId** (string) - The Discogs Label ID

#### `$discogs->labelReleases($labelId)`

* **$labelId** (string) - The Discogs Label ID

#### `$discogs->search($params)`

* **$params** (array) - An array of search parameters. A full list is available [here](https://www.discogs.com/developers/#page:database,header:database-search)

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
