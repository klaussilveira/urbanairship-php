# Urban Airship PHP
[![Build Status](https://secure.travis-ci.org/klaussilveira/urbanairship-php.png)](http://travis-ci.org/klaussilveira/urbanairship-php)

Urban Airship PHP is a drop-in library that provides a simple way to integrate Urban Airship services into your web application.
It abstracts devices and notifications in order to provide a coherent and elegant interface to work with push notifications,
batch pushes, broadcasts, device registration and more.

## Authors and contributors
* [Klaus Silveira](http://www.klaussilveira.com) (Creator, developer)

## License
[New BSD license](http://www.opensource.org/licenses/bsd-license.php)

## Supports
* iOS, Blackberry device registration/deactivation
* Android, iOS, Blackberry push notifications and broadcasts
* Android, iOS, Blackberry batch pushes

## TODO
* Rich Push API
* Feed API
* Subscription API

## Usage
The library is very easy to use. You just need to setup the client:

```php
<?php

use UrbanAirship\Client;
use UrbanAirship\Push\AppleNotification;
use UrbanAirship\Device\AppleDevice;

$client = new Client('your_application_key', 'your_master_secret');

// Simple broadcast
$notification = new AppleNotification();
$notification->setAlert('Hey dude!');
$notification->setBadge(1);
$client->push($notification);

// Simple notification, with device
$device = new AppleDevice('FE66489F304DC75B8D6E8200DFF8A456E8DAEACEC428B427E9518741C92C6660');
$device->setAlias('Luke Skywalker')->addTag('republic')->addTag('pilot');

$notification = new AppleNotification();
$notification->addDevice($device);
$notification->setAlert('Hey dude!');
$notification->setBadge(1);
$client->push($notification);

```

For further information and examples, check the test suite.