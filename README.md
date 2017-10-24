iZettle Api
===============
The iZettle Api provides a simple integration of the [iZettle Api][izettleapi] for your PHP project.

[![Build Status](https://travis-ci.org/LauLamanApps/iZettleApi.svg?branch=master)](https://travis-ci.org/LauLamanApps/iZettleApi)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LauLamanApps/iZettleApi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/LauLamanApps/iZettleApi/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/LauLamanApps/iZettleApi/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/LauLamanApps/iZettleApi/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/laulamanapps/Izettle-api/v/stable)](https://packagist.org/packages/laulamanapps/izettle-api)
[![License](https://poser.pugx.org/laulamanapps/Izettle-api/license)](https://packagist.org/packages/laulamanapps/izettle-api)

**Warning: Currently in development**

**TODO**
- Finalize integration


Installation
------------
With [composer](http://packagist.org), add:

```bash
$ composer require laulamanapps/izettle-api
```

Usage
-----

Get yourself an access token. you'll need an `clientId` and `clientSecret` for this (to get one apply [here](https://www.izettle.com/api-access/))
for performance benefits, it might be wise to store the access token in a cache like [Redis](https://redis.io/).
```php
use GuzzleHttp\Client;

$accessTokenFactory = new AccesTokenFactory(new Client(), 'clientId', 'clientSecret');

$accessToken = $accessTokenFactory->getFromUserLogin('john.doe@example.com', 'password');

```

### Make your first API call

```php

use GuzzleHttp\Client;

$purchaseClient = new PurchaseClient(new Client(), $accessToken);
$purchaseHistory = $purchaseClient->getPurchaseHistory();

$productClient = new ProductClient(new Client(), $accessToken);
$categories = $productClient->getCategories();
```


Credits
-------

iZettle Api has been developed by [LauLaman][LauLaman].

[izettleapi]: https://github.com/iZettle/api-documentation
[LauLaman]: https://github.com/LauLaman
