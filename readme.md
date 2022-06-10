# PayeerTradeAPI

```php
<?php

use PayeerTrade\PayeerTradeAPI;

$apiId = '123d074b-1231-4fae-123e-d836f8304901';        // example
$apiSecretKey = '123tav2b12sF1fkC';        // example

$payeerTradeAPI = new PayeerTradeAPI(
    apiId: $apiId,
    apiSecretKey:$apiSecretKey
);

PayeerTradeAPI::account();
PayeerTradeAPI::ticker();
```
## Installation

### With Composer

```
$ composer require veselkovsergey/payeertrade:dev-master
```

```json
{
    "require": {
      "veselkovsergey/payeertrade": "dev-master"
    }
}
```