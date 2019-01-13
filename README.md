# Netatmo Provider for OAuth 2.0 Client

[![Latest Version](https://img.shields.io/github/tag/rugaard/oauth2-netatmo.svg?style=flat-square)](https://github.com/rugaard/oauth2-netatmo/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/rugaard/oauth2-netatmo/master.svg?style=flat-square)](https://travis-ci.org/rugaard/oauth2-netatmo)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/rugaard/oauth2-netatmo.svg?style=flat-square)](https://scrutinizer-ci.com/g/rugaard/oauth2-netatmo/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/rugaard/oauth2-netatmo.svg?style=flat-square)](https://scrutinizer-ci.com/g/rugaard/oauth2-netatmo)
[![Total Downloads](https://img.shields.io/packagist/dt/rugaard/oauth2-netatmo.svg?style=flat-square)](https://packagist.org/packages/rugaard/oauth2-netatmo)

This package provides Netatmo OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```bash
composer require rugaard/oauth2-netatmo
```

## Usage

Usage is the same as The League's OAuth client, using `\Rugaard\OAuth2\Client\Netatmo\Provider\Netatmo` as the provider.

#### Initialize Provider

```php
$provider = new \Rugaard\OAuth2\Client\Netatmo\Provider\Netatmo([
    'clientId'          => '{netatmo-client-id}',
    'clientSecret'      => '{netatmo-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url'
]);
```

#### Generate Authorization URL
```php
// Authorize with default scopes.
$url = $provider->getAuthorizationUrl();

// Authorize with other scopes.
$url = $provider->getAuthorizationUrl([
    'scope' => ['read_scope', 'write_scope']
]);
```

To see which scopes are available, please refer to the [scopes section](#netatmo-scopes).

#### Generate and refresh tokens.

For further usage of this package please refer to the [core package documentation on "Authorization Code Grant"](https://github.com/thephpleague/oauth2-client#usage).

## Netatmo scopes

**Note**: This provider will always request the `read_thermostat` scope during authorization. Without this scope, Netatmo does not provide a generic way to fetch information about the "resource owner".

### List of available scopes

**Weather Station**
- `read_station`

**Thermostat**
- `read_thermostat`
- `write_thermostat`

**Home Coach**
- `read_homecoach`

**Welcome**
- `read_camera`
- `write_camera`
- `access_camera` _(requires app to have granted scope by Netatmo)_

**Presence**
- `access_presence` _(requires app to have granted scope by Netatmo)_

**Smoke Alarm**
- `read_smokedetector`

## Testing

```bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/rugaard/oauth2-netatmo/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Morten Rugaard](https://github.com/rugaard)
- [All Contributors](https://github.com/rugaard/oauth2-netatmo/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/rugaard/oauth2-netatmo/blob/master/LICENSE) for more information.