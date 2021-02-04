# V1 Migration Guide

We understand that introducing breaking changes to the way you interact with our systems is not completely appreciated.
Especially since as of February 2021, we will be deprecating the version 1 of our client API.

This is why we would like to help everyone migrate from version `1.*` to version `2.0.0`.

If you're running into problems or have a question at any point of your migration, feel free to [open up a new issue](https://github.com/Trunkrs/Trunkrs-SDK-PHP/issues/new/choose). We usually respond within 1-2 hours.

> Using the underlying V2 API requires you to have new credentials. You can start implementing SDK `2.0.0` already without
> having these new credentials.

## Settings

The new V2 api requires the use of a new API key instead of a set of credentials. It's not yet required to use the new v2 API.
Your old credentials will continue to work with our v1 API version.

To keep using your old credential set and the v1 API. The API version will need to be set to v1 as well.
```php
\Trunkrs\SDK\Settings::setApiVersion(1);
```

As of SDK `2.0.0` the default version of the API will be `2`.

## Shipments

Most of the details are the same in regard to shipments. 
The biggest change is that as of `2.0.0` the shipment is not identified anymore by its id but by its Trunkrs number.

This might have some implications for your way of storing references to shipments as the main identifier has changed from an integer to a string.

### Creating a shipment

`2.0.0` of the SDK introduces a more granular way of supplying shipment details.
This new way is even partially supported in the V1 API.

V1 Create:
```php
$details = new \Trunkrs\SDK\ShipmentDetails();
$details->reference = 'your-order-ref';
// We define the amount of parcels in this shipment
$details->quantity = 2;


$sender = new \Trunkrs\SDK\Address();
$recipient = new \Trunkrs\SDK\Address();

$shipment = \Trunkrs\SDK\Shipment::create($details, $sender, $recipient);
```

`2.0.0` V1 create:
```php
$details = new \Trunkrs\SDK\ShipmentDetails();

$parcel1 = new \Trunkrs\SDK\Parcel();
$parcel1->reference = 'your-order-reference';

$parcel2 = new \Trunkrs\SDK\Parcel();
$parcel2->reference = 'your-order-reference';

$details->parcels = [$parcel1, $parcel2];

$details->sender = new \Trunkrs\SDK\Address();
$details->recipient = new \Trunkrs\SDK\Address();

$shipments = \Trunkrs\SDK\Shipment::create($details);
```

> **Note:** International shipping, the use of the feature codes and the service level are not available when using API version 1.

### Finding a shipment

V1 Find:
```php
\Trunkrs\SDK\Shipment::find($shipmentId);
```

`2.0.0` V1 Find:
```php
\Trunkrs\SDK\Shipment::findById($shipmentId);
```
