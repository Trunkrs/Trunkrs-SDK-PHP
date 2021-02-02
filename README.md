# Trunkrs SDK for PHP

![CI](https://github.com/Trunkrs/Trunkrs-SDK-PHP/workflows/CI/badge.svg?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Trunkrs/Trunkrs-SDK-PHP/badge.svg?branch=master)](https://coveralls.io/github/Trunkrs/Trunkrs-SDK-PHP?branch=master)
[![Latest Stable Version](https://poser.pugx.org/trunkrs/sdk/version)](https://packagist.org/packages/trunkrs/sdk)
[![License](https://poser.pugx.org/trunkrs/sdk/license)](https://packagist.org/packages/trunkrs/sdk)

The Trunkrs software development kit for the public client SDK. With this PHP SDK you can manage your shipments, shipment states and webhooks within our system.

> ### Migrate from version 1
> Check out [our migration guide](MIGRATE.md) if you wish to migrate your v1 implementation of the Trunkrs SDK.

## Requirements

PHP 7.0 and later.

## Installation

You can install the SDK via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require trunkrs/sdk
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Dependencies

The SDK requires the following extensions in order to work properly:

-   [`json`](https://secure.php.net/manual/en/book.json.php)
-   [`guzzle/guzzle`](https://github.com/guzzle/guzzle) (optional, can be replaced)

If you use Composer, these dependencies should be handled automatically.

## Getting started

Setup the SDK settings before usage by supplying your merchant credentials. If you don't have any credentials yet, please contact [Trunkrs](https://trunkrs.nl) for more information.

```php
\Trunkrs\SDK\Settings::setApiKey("your-trunkrs-api-key");
```

### Using staging

To make use of the Trunkrs staging environment, which has been supplied to test your implementation with our system.
The SDK can be switched easily.

```php
\Trunkrs\SDK\Settings::useStaging();
```

Both API endpoints and the tracking URL's will point to the staging environment.

## Shipments

### Create a shipment

A shipment can be created through the `Shipment` class. It exposes a static method `Shipment::create(...)`.

```php
$details = new \Trunkrs\SDK\ShipmentDetails();

$parcel = new \Trunkrs\SDK\Parcel();
// Set the reference of the parcel. This is required.
$parcel->reference = 'your-order-reference';

$details->parcels = [
    // Define which parcels are part of this shipment
    $parcel,
];

$details->sender = new \Trunkrs\SDK\Address();
// Set the pickup address properties.

$details->recipient = new \Trunkrs\SDK\Address();
// Set the delivery address properties.

$shipments = \Trunkrs\SDK\Shipment::create($details);
```

> #### International shipping
> When shipping internationally we require you to define the contents of a parcel as well as the volume and weight of the parcel.

### Retrieve shipment details

Details for a single shipment can be retrieved through its identifier by calling the `Shipment::find($trunkrsNr)` method.

```php
$shipment = \Trunkrs\SDK\Shipment::find('4000002123');
```

### Retrieve shipment history

Your shipment history can be listed in a paginated manner by using the `Shipment::retrieve($page)` method.
Every returned page contains a maximum of 50 shipments.

```php
$shipments = \Trunkrs\SDK\Shipment::retrieve();
```

### Cancel a shipment

Shipments can be canceled by their identifier or simply through the `cancel()` method on an instance of a `Shipment`.

The `Shipment` class exposes the `cancelByTrunkrsNr($trunkrsNr)` static method:
```php
\Trunkrs\SDK\Shipment::cancelByTrunkrsNr('4000002123');
```

An instance of the `Shipment` class also exposes a convenience method `cancel()`:

```php
$shipment = \Trunkrs\SDK\Shipment::find('4000002123');

$shipment->cancel();
```

## Shipment State

To retrieve details about the shipment's current state and the current owner of the shipment.
The `ShipmentState` class can be used which exposes the static `forShipment($shipmentId)` method.

```php
$status = \Trunkrs\SDK\ShipmentState::forShipment('4000002123');
```

## Web hooks

To be notified about shipment state changes, Trunkrs has created a webhook notification service.
The SDK allows the registration of a callback URL for notifications through this service.

### Register subscription

The `Webhook` class exposes a static method called `register($webhook)` which allows the registration of new web hooks:

```php
$webhook = new \Trunkrs\SDK\Webhook();
$webhook->callbackUrl = "https://your.web.service/shipments/webhook";
$webhook->sessionHeaderName = 'X-SESSION-TOKEN';
$webhook->sessionToken = "your-secret-session-token";
$webhook->event = \Trunkrs\SDK\Enum\WebhookEvent::ON_STATE_UPDATE;

\Trunkrs\SDK\Webhook::register($webhook);
```

### Retrieve active subscriptions

Your active webhook subscriptions can be listed using  `Webhook::retrieve()`.

```php
$webhooks = \Trunkrs\SDK\Webhook::retrieve();
```

### Cancel subscription

Canceling a web hook subscription can be done using `Webhook::removeById($webhookId)` or the instance method on an instance of webhook.

```php
$webhookId = 100;

\Trunkrs\SDK\Webhook::removeById($webhookId);
```

```php
$webhook = \Trunkrs\SDK\Webhook::find(100);

$webhook->remove();
```
















