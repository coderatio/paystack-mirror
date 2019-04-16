
<img src="http://github.com/paystack-mirror/docs/logo.png"/>

The missing Paystack PHP Library. 
___
## Overview
Paystack Mirror is a clean, simple and fluent php library for paystack payment gateway. This library is birthed out of the fact that i needed something flexible than what is in existance for myself and the php community.

### What was wrong with the existing one?
The official paystack library for php is the yabacon/paystack-php on github. It's cool. Supports almost all paystack end-points and some other cool features like a dedicated Fee class, MetaDataBuilder class and Event handler class. Many people have been using the library but i wanted something that can give people freedom over what they do. I wanted something that supports multiple accounts at once. It's why i decided to work on this library. 

### What is unique about the library?
1. The conversion of paystack end-points to full fletch extendable php classes.
2. Supports for multiple accounts
3. Clean and better event handler class
4. Fluent Params Builder class
5. Nigerian money conversion e.g 1k => 1,000 naira and 1,000 naira => 100000 kobo and even (millions(xM), billions(xB), trillions(xT)) to kobo e.t.c. 

## Server Requirements
> PHP version ^7.1.3 requirement was done intentionally. Reason been that it's safer, better and faster than 5.6 and 7.0. So, if you have been using php version less than that, kindly upgrade before using this library.
* php ^7.1.3
* cURL extension enabled
* OpenSSL extension enabled

> This library has suppports for all Paystack end-points which we refers to as **Actions**. _Let's take a look at the available actions._

## List of actions groups
Below are actions groups supported by the library in alphabetical order.

1. [BULK CHARGES](docs/pages/bulk-charges.md)
2. [CHARGES](docs/pages/charges.md)
3. CONTROL PANEL
4. CUSTOMERS
5. INVOICES
6. MISCELLANEOUS
7. PAGES
8. PLANS
9. REFUNDS
10. SETTLEMENTS
11. SUB-ACCOUNTS
12. SUBSCRIPTIONS
13. TRANSACTIONS
14. TRANSFER RECIPIENTS
15. TRANSFERS
16. TRANSFERS CONTROL
17. VERIFICATIONS

>**Note:** To see the list of all actions under each action groups available on this library, kindly [Click Here](https://github.com/coderatio/paystack-mirror/tree/master/src/Actions). 

> Arrangements are on the way to list all of them like the first two above.

## Installation
```
composer require coderatio/paystack-mirror
```

## Usage
### With Single Paystack Account

_Method One_
```php

require 'venodr/autoload.php';

use Coderatio\PaystackMirror\PaystackMirror;
use Coderatio\PaystackMirror\Actions\Transactions\ListTransactions;

$queryParams = new ParamsBuilder();
$queryParams->perPage = 10;

$result = PaystackMirror::run($secretKey, new ListTransactions($queryParams));

echo $result->getResponse();

```

_Method Two_
```php

require 'venodr/autoload.php';

use Coderatio\PaystackMirror\PaystackMirror;
use Coderatio\PaystackMirror\Actions\Transactions\ListTransactions;

$queryParams = new ParamsBuilder();
$queryParams->perPage = 10;

$result = PaystackMirror::run($secretKey, ListTransactions::class, $queryParams);

echo $result->getResponse();

```

_Method Three_
```php

require 'venodr/autoload.php';

use Coderatio\PaystackMirror\PaystackMirror;
use Coderatio\PaystackMirror\Actions\Transactions\ListTransactions;

$queryParams = new ParamsBuilder();
$queryParams->perPage = 10;

$result = PaystackMirror::setKey($secretKey)->mirror(new ListTransactions($queryParams));

echo $result->getResponse();

```

_Method Four_
```php

require 'venodr/autoload.php';

use Coderatio\PaystackMirror\PaystackMirror;
use Coderatio\PaystackMirror\Actions\Transactions\ListTransactions;

$queryParams = new ParamsBuilder();
$queryParams->perPage = 10;

$result = PaystackMirror::setKey($secretKey)->mirror(ListTransactions::class, $queryParams);

echo $result->getResponse();

```
>**Notice:** By default, `->getResponse()` returns a json object. But, you can chain `->asArray()` to convert the response to php `array` or `->asObject()` to conver the response to php `object` at runtime.

### With Multiple Paystack Accounts
With this library, you can mirror single action on multiple paystack accounts. This is super cool for multitenants applications or firms with multiple paystack accounts.

Let's see how to do it.
```php

// Let's use ParamsBuilder to build our data to be sent to paystack.

// First account
$firstAccountParams = new ParamsBuilder();
$firstAccountParams->first_name = 'Josiah';
$firstAccountParams->last_name = 'Yahaya';
$firstAccountParams->email = 'example1@email.com';
$firstAccountParams->amount = short_naira_to_kobo('25.5k');
$firstAccountParams->reference = PaystackMirror::generateReference();

$firstAccount = new ParamsBuilder();
$firstAccount->key = $firstAccountKey;
$firstAccount->data = $firstAccountParams;

// Second account
$secondAccountParams = new ParamsBuilder();
$secondAccountParams->first_name = 'Ovye';
$secondAccountParams->last_name = 'Yahaya';
$secondAccountParams->email = 'example2@email.com';
$secondAccountParams->amount = short_naira_to_kobo('10k');
$secondAccountParams->reference = PaystackMirror::generateReference();

$secondAccount = new ParamsBuilder();
$secondAccount->key = $secondAccountKey;
$secondAccount->data = $firstAccountParams;

$results = PaystackMirror::setAccounts([$firstAccount, $secondAccount])
    ->mirrorMultipleAccountsOn(new InitializeTransaction());
    
// OR

$results = PaystackMirror::setAccounts([$firstAccount, $secondAccount])
    ->mirrorMultipleAccountsOn(InitializeTransaction::class);

foreach ($results as $result) {
    // Do something with $result.
    
   ...
   
   // The $result variable holds two main object properties; 
   // $result->account which holds an account key and $result->response which holds the response for an account. 
   // The best thing to do is to dump the $result variable to see what's contain there in.
}
```

You can overwrite all the accounts data by providing your params on the action. When that is done, the library will use the parameters supplied on the action for all the accounts instead e.g
```php

    $actionParams = new ParamsBuilder();
    $actionParams->email = 'johndoe@email.com';
    $actionParams->amount = naira_to_kobo('1,000');
    $actionParams->reference = PaystackMirror::generateReference();

    $results = PaystackMirror::setAccounts([$firstAccount, $secondAccount])
        ->mirrorMultipleAccountsOn(new InitializeTransaction($actionParams));
        
    // OR
    
    $results = PaystackMirror::setAccounts([$firstAccount, $secondAccount])
            ->mirrorMultipleAccountsOn(InitializeTransaction::class, $actionParams);
        
```
>**Quick Note:** All the query or body params used on paystack api documentation site are all available in this library. The only different is, they must be sent as an array or as `ParamBuilder::class` object. 

## Create your action
One good thing about this library is the ability to plug and play actions. You can replace existing actions by creating yours. 

```php
<?php

use \Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;

class MyCustomAction extends Action
{
    // The paystack endpoint for this action
    protected $url = '';
    
    public function handle(CurlService $curlService) : void 
    {
        // Use the $curlService to handle this action's request.
        // E.g to send a post request, see below:
        
        $curlService->post($this->url, $this->getData());
    }
}

``` 

>Please note that `$this->data` property returns an array. If you want to send parameters as json to paystack, use `$this->getData()`.

## Webhook Event Handling
This library ships with a fluent Event handling class to be used at your webhook url set on your paystack dashboard. See example below on how to listen to different events.

```php
<?php

use Coderatio\PaystackMirror\Events\Event;

// $secretKeys array structure should be like this:
$secretKeys = [
    'test' => 'sk_testxxxxxxxxxxxxx',
    'live' => 'sk_live_xxxxxxxxxxxxxx'
];

$eventData = Event::capture()->thenValidate(string $secretKey or array $secretKeys)
    ->thenListenOn('subscription.create')->thenGetData();

// Do something with the $eventData

```
## Create a separate event class
With this library, you can write a separate event class for a single event. For example, the `subscription.create` event can be created like this:
```php
<?php

namespace Coderatio\PaystackMirror\Events;

class SubscriptionCreated implements ActionEvent
{
    public static function validate($keys): Event
    {
        return Event::capture()->thenValidate($keys)
            ->thenListenOn('subscription.create');
    }
}

```

This can then be used like this:

```php
<?php

use Coderatio\PaystackMirror\Events\SubscriptionCreated;

$eventData = SubscriptionCreated::validate($key)->thenGetData();

// Or 

$event = SubscriptionCreated::validate($key)->thenGetEvent();

```
Here, you can see that we are just implementing the `ActionEvent::class` interface and extending the `Event::class` on the `::validate()` method.

## Addons
The library has a few of in-built functionalities to do somethings quicker and better. Let's take a look at them:

#### 1. Nairas to kobo
Since paystack accepts amount in kobo only, there should be a quick way to do that. Below are helper functions to help you out.
```php
<?php

// Normal naira amount to kobo
$amount = naira_to_kobo('1000'); // Returns: 100000

// Naira with commas
$amount = naira_to_kobo('1,000'); // Returns: 100000

// Human readable nairas to kobo
$amount = short_naira_to_kobo('2k'); // Returns: 200000

$amount = short_naira_to_kobo('1.5m'); // Returns: 150000000

``` 
>**Note:** The `short_naira_to_kobo()` helper function, supports only `k` as thousands, `m` as millions, `b` as billions and `t` as trillions notations.

#### 2. Reference Generator
You can easily generate especially, transaction reference easily by doing this:

```php
<?php

use \Coderatio\PaystackMirror\PaystackMirror;

$reference = PaystackMirror::generateReference();

```

## Todo
1. Build a dedicated docs site

## Tests
```bash
composer test

// OR

./vendor/bin/phpunit
```

## Contributions
Correcting a typographical error is a huge a contribution to this project. Do well to do that. You can fork the repo and send pull request or reach out easily to me via twitter here => [Josiah Ovye Yahaya](https://twitter.com/josiahoyahaya).

## Collaborators
1. [Josiah O. Yahaya](http://github.com/coderatio)

## Licence
This project is built and used with `GPL` licence.