# Coresender PHP SDK

This is the officially supported PHP library for [Coresender](https://coresender.com). It allows you to quickly and easily integrate with our API and improve your email deliverability.

### Prerequisites

* PHP version 7.1+
* The Coresender service. You can start with a free 100 emails/month developer plan and move to one of our [pricing plans](https://coresender.com/pricing) when you're done.

### Installation

To install the SDK, you will need to be using [Composer](http://getcomposer.org/).

The Coresender PHP SDK is not hard coupled to Guzzle, Buzz or any other library that sends
HTTP messages. Instead, it uses the [PSR-18](https://www.php-fig.org/psr/psr-18/) client abstraction.
This will give you the flexibility to choose what
[PSR-7 implementation and HTTP client](https://packagist.org/providers/php-http/client-implementation)
you want to use. 

Run the following command to get started: 

```bash
composer require coresender/coresender-sdk-php kriswallsmith/buzz nyholm/psr7
```

### Usage

Here's how to send an email using the SDK:

```php

require 'vendor/autoload.php';

use Coresender\Coresender;
use Coresender\Helpers\EmailBuilder;


$builder = new EmailBuilder();
$builder
    ->setFrom('jean.luc@example.com', 'Jean-Luc Picard')
    ->addToRecipient('geordi@example.com', 'Geordi La Forge')
    ->setSubject('I need engines')
    ->setBodyText('Geordi, I need engines, now!')
    ->setBodyHtml('<p>Geordi, I need engines, <strong>now!</strong></p>')
;

$sendingApi = Coresender::createSendEmailApi('<<INSERT SENDING ACCOUNT ID>>', '<<INSERT SENDING ACCOUNT API KEY>>');
$sendingApi->addToBatch($builder->getEmail());
$sendingApi->execute();
```

### Contribute

The Coresender PHP SDK is an open-source project released under MIT license. We welcome any contributions!

You can help by:
* Writing new code
* Creating issues if you find problems
* Helping others with their issues
* Reviewing PRs
