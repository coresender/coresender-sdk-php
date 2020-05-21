# Coresender PHP SDK

One Paragraph of project description goes here

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
PHP 7.1
```

### Installing

```
composer require coresender/coresender-sdk-php kriswallsmith/buzz nyholm/psr7
```

End with an example of getting some data out of the system or using it for a little demo


### Usage

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

$sendingApi = Coresender::createSendingApi('<<INSERT SENDING ACCOUNT ID>>', '<<INSERT SENDING ACCOUNT KEY>>');
$sendingApi->scheduleEmail($builder->getEmail());
$sendingApi->execute();
```