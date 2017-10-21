iZettleApi
===============
The iZettleApi provides a simple integration of the [iZettle Api][izettleapi] for your PHP project.

**Warning: Currently in development**

[![Build Status](https://travis-ci.org/LauLamanApps/iZettleApi.svg?branch=master)](https://travis-ci.org/LauLamanApps/iZettleApi)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LauLamanApps/iZettleApi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/LauLamanApps/iZettleApi/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/LauLamanApps/iZettleApi/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/LauLamanApps/iZettleApi/?branch=master)

**TODO**
- Create finalize intergration
- Clean Client.php & AccessToken.php


Installation
------------
With [composer](http://packagist.org), add:

```bash
$ composer require laulamanapps/izettle-api
```

Usage
-----

Get yourself an accesstoken. you'll need an `clientId` and `clientSecret` for this (to get one apply [here](https://www.izettle.com/api-access/))
for performance benefits it might be wise to store the accesstoken in a cache like [Redis](https://redis.io/).
```php
$accessToken = AccessToken::getFromUserLogin(
    'clientId',
    'clientSecret',
    'john.doe@example.com',
    'password'
);

```

### Make your first API call

```php
$client = new Client($accessToken);
$purchaseHistory = $client->getPurchaseHistory();
```


Credits
-------

iZettleApi has been developed by [LauLaman][LauLaman].

[izettleapi]: https://github.com/iZettle/api-documentation
[LauLaman]: https://github.com/LauLaman
