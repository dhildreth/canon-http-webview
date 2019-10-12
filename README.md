Canon HTTP WebView PHP Client
=============================

Canon HTTP WebView is a PHP client that makes it easy to integrate the Canon
Network Camera Protocol Spec., or HTTP WebView Protocol Specifications into your
project.

## Features

- Create a session, control the camera, and download images from Canon network 360 cameras.
- Tested using VB-C60 camera, but should also support VB-C500D/VD, VB-C300, VB-C50i/R, and VB-C50Fi/FSi.
- Example test script to demonstrate how to use the WVHttp client.
- Included original PDF of WebView Protocol Specifications for reference.

```php
use WVHttp\Client;

$client = new Client([
    'base_uri' => 'http://www.foo.com/1.0/',
    'auth' => [
        'username',
        'password',
    ],
    'timeout' => 20
]);

$client->open(['v' => 'jpg:640x480:5', 'p' => '50']);
$client->claim();
$client->control([
    'pan' => 15.75 * 100,
    'tilt' => -4.25 * 100,
    'zoom' => 55.8 * 100,
]);

$client->image('wvhttp_'.date('Y-m-d-H-s').'.jpg');
$client->yield();
$client->close();
```

## Installing

The recommended way to install canon-http-webview is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version:

```bash
php composer.phar require dhildreth/canon-http-webview
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

You can then later update using composer:

 ```bash
php composer.phar update
 ```


## Shortcomings

- No event driven functionality, rely on sleep() calls.
- No video downloading capability (video.cgi).
- Old compatible commands not implemented.
- Thrown exceptions and user input validation could be implemented.
- panorama.cgi not implemented.
