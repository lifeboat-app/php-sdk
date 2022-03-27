# Lifeboat.app - PHP SDK

[![Build Status](https://app.travis-ci.com/lifeboat-app/php-sdk.svg?branch=main)](https://app.travis-ci.com/lifeboat-app/php-sdk)
[![Latest Stable Version](http://poser.pugx.org/lifeboat/php-sdk/v)](https://packagist.org/packages/lifeboat/php-sdk)
[![License](http://poser.pugx.org/lifeboat/php-sdk/license)](https://packagist.org/packages/lifeboat/php-sdk)

This SDK is meant to help developers to
build apps that access the Lifeboat API.
<br/>
[lifeboat.app](https://lifeboat.app)

## UNDER DEVELOPMENT
This SDK is still under development.

---
The Lifeboat PHP SDK enables site owners and app developers
to leverage the Lifeboat API with their software.

## Requirements
PHP 7.2 or later

## Composer
You can install this SDK via [Composer](https://getcomposer.org).
Run the command
```
composer require lifeboat/php-sdk
```

To use this SDK you need to use [Composer](https://getcomposer.org) autoload.
```php
require_once('vendor/autoload.php');
```


## Setup
1. Create a developer account on [dev.lifeboat.app](https://dev.lifeboat.app)
2. Register an app


### NOTE
**NEVER share your app credentials**

## Getting Started
### Invoke the client
Initialise the SDK and redirect the user to the Lifeboat OAuth
service. The OAuth service will automatically login and capture
user authorisation to use your app.
```php
// The SDK requires sessions to store and cache data
session_start();

$client = new \Lifeboat\App(
    '<APP ID>',
    '<APP SECRET>'
);

// The SDK will create a strong challenge key
// and automatically store it in the session
$challenge = $client->getAPIChallenge();

// Redirect the user to the auth screen
$redirect_url = $client->getAuthURL(
    'https://my.app/oauth/process_url',
    'https://my.app/oauth/error_url',
    $challenge
);
header("Location: {$redirect_url}");
exit;
```

##### Processing Page
```php
// https://my.app/oauth/process_url
session_start();
$client = new \Lifeboat\App(
    '<APP ID>',
    '<APP SECRET>'
);

// This code will be returned by the oauth service to provide
// you with temporary access to the logged in user account.
$code = $_GET['code'];

// Get an access token
// The SDK will automatically store the access token in sessions
//
// If the user has access to multiple stores
// the SDK will also automatically select the active store
// based on the user's selection durin OAuth
$access_token = $client->fetchAccessToken($code);
```

### Using the client without user interaction
Sometimes you might need to perform actions on the user's
store without that user actively interacting with your app.
A common usecase is retreiving products on a cron to perform
analysis or other checks.
<br /><br />
To do this you need to know 2 important parameters:
- `site_key`: The store's site_key you want to access
- `site_host`: The store's master domain


**Note: The user must first authorise your app before you can use this method**
```php
$client = new \Lifeboat\App(
    '<APP ID>',
    '<APP SECRET>'
);

// Let the SDK know which store you'll be interacting with
$client->setActiveSite($site_host, $site_key);
```
*From here on forward you can keep using the `$client` as if
the user has logged in. The SDK & Lifeboat OAuth will automatically
check that your app has been authorised by the user.*

### Basic Usage
Creating, manipulating and deleting objects from here on is
done completely through the SDK.
```php
$product = $client->product->create([
    'Title' => 'MyProduct',
    'SKU' => 'xxx',
    'TrackStock' => true
]);

// Makes the necessary calls to the API to store the object
$product->save();
```

### Examples
You can see a working example in the **examples** directory of this
repository.

- `Auth.php` - An example auth controller for your front-end
- `Cron.php` - An example cron controller
- `Store.php` - An example object to save store information

[![Latest Unstable Version](http://poser.pugx.org/lifeboat/php-sdk/v/unstable)](https://packagist.org/packages/lifeboat/php-sdk)
[![codecov](https://codecov.io/gh/lifeboat-app/php-sdk/branch/main/graph/badge.svg)](https://codecov.io/gh/lifeboat-app/php-sdk)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lifeboat-app/php-sdk/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/lifeboat-app/php-sdk/?branch=main)

