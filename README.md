# Lifeboat.app - PHP SDK

[![Latest Stable Version](http://poser.pugx.org/lifeboat/php-sdk/v)](https://packagist.org/packages/lifeboat/php-sdk)
[![Total Downloads](http://poser.pugx.org/lifeboat/php-sdk/downloads)](https://packagist.org/packages/lifeboat/php-sdk)
[![Latest Unstable Version](http://poser.pugx.org/lifeboat/php-sdk/v/unstable)](https://packagist.org/packages/lifeboat/php-sdk)
[![License](http://poser.pugx.org/lifeboat/php-sdk/license)](https://packagist.org/packages/lifeboat/php-sdk)
[![PHP Version Require](http://poser.pugx.org/lifeboat/php-sdk/require/php)](https://packagist.org/packages/lifeboat/php-sdk)

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
PHP 7.4 or later

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
#### For site owners/testers
1. Create an account on [accounts.lifeboat.app/register](https://accounts.lifeboat.app)
2. Create your online shop
3. Go to your profile [accounts.lifeboat.app/profile/edit](https://accounts.lifeboat.app/profile/edit)
4. Create an API Key

#### For App developers
1. Create a developer account on [dev.lifeboat.app](https://dev.lifeboat.app)
2. Register an app


## Getting Started
### Invoke the client
#### Using API Key / Secret
This method should only be used if you are the owner of the site.
<br />
**NEVER** give API credentials to third-party apps.
```php
// Prepares a connection to the API
$client = new \Lifeboat\Client(
    '<API KEY>',
    '<API SECRET>'
);
```

<br />

#### Using an App
This method should be used when creating an app.
<br />
This method ensures a secure environment for your app, and the merchant
without the need to ever share credentials.
```php
session_start();

$app = new \Lifeboat\App(
    '<APP ID>',
    '<APP SECRET>'
);

// Store this challenge to verify the request
// This is to mitigate against any man in the middle attacks
$challenge = $app->getAPIChallenge();
$_SESSION['lifeboat_challenge'] = $challenge;

// Redirect the user to the auth screen
$redirect_url = $app->getAuthURL(
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
$app = new \Lifeboat\App(
    '<APP ID>',
    '<APP SECRET>'
);

// This code will be returned by the oauth service to provide
// you with temporary access to the logged in user account,
// so that your app can create an access token.
$code = $_GET['code'];

// Get an access token
$access_token = $app->fetchAccessToken($code);

// Save this token so the user doesn't need to login again
// with every request.
// Tokens expire every 2 hours
$_SESSION['lifeboat_access_token'] = $access_token;

// Set the access token
$app->setAccessToken($access_token);
```

<br />

### Selecting the active site
The merchant might have multiple sites in his/her name.
Thus, you need to announce to the Lifeboat client which site will be accessed.
```php
// Get a list of all the sites the merchant has access to
$sites = $client->getSites();

// Show some sort of selection screen...
$site = [
    'name' => '...',
    'domain' => '...',
    'api_url' => '...',
    'site_key' => '...'
];

// Specify the active site
$client->setActiveSite($site['domain'], $site['site_key']);
```

**IMPORTANT**
<br />
The API is always accessible ONLY from the master domain of a
Lifeboat site. If the merchants change their master domain,
the active site has to be updated.

<br />

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


