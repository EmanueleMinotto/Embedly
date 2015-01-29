Embedly [![Build Status](https://travis-ci.org/EmanueleMinotto/Embedly.svg)](https://travis-ci.org/EmanueleMinotto/Embedly)
-------

Library for [embed.ly APIs](http://embed.ly/api) based on [Guzzle 5](http://docs.guzzlephp.org/en/latest/).

## Usage

This library provides 3 API: [embed.ly/docs/api](http://embed.ly/docs/api).

Some APIs need an API key you can set in the constructor os using the `setApiKey` method.
The second method of the constructor can be a Guzzle HTTP client to use as an alternative of the default one.

### Embed

Options are available at http://embed.ly/docs/api/embed/arguments

For this method the `$api_key` can be null.

Currently the `url` attribute is required.

```php
use EmanueleMinotto\Embedly\Client;

$client = new Client($api_key);

$embed = $client->oembed([
    'url' => 'http://www.example.com/',
]);
```

### Extract

Options are available at http://embed.ly/docs/api/embed/arguments

Currently the `url` attribute is required.

```php
use EmanueleMinotto\Embedly\Client;

$client = new Client($api_key);

$extracted = $client->extract([
    'url' => 'http://www.example.com/',
]);
```

### Display

The first argument can be `NULL`, `crop`, `resize` or `fill`.

Options are available at

 * http://embed.ly/docs/api/display/endpoints/1/display
 * http://embed.ly/docs/api/display/endpoints/1/display/crop
 * http://embed.ly/docs/api/display/endpoints/1/display/resize
 * http://embed.ly/docs/api/display/endpoints/1/display/fill

Currently the `url` attribute is required.

```php
use EmanueleMinotto\Embedly\Client;

$client = new Client($api_key);

$content = $client->display('resize', [
    'url' => 'http://placehold.it/250x100',
    'color' => '000',
    'height' => 150,
    'width' => 150,
]);
```