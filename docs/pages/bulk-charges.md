# BULK CHARGES
## InitializeBulkCharge
```php
// Class

Coderatio\PaystackMirror\Actions\BulkCharges\InitializeBulkCharge::class

```
Send an array of objects with authorization codes and amount in kobo so paystack can process transactions as a batch.

**Body Params**
* **authorization** - Authorization code
* **amount** - Amount

## ListBulkChargeBatches
```php
// Class

Coderatio\PaystackMirror\Actions\ListBulkChargeBatches::class

```
This lists all bulk charge batches created by the integration. Statuses can be active, paused, or complete.

**Query Params**
* **perPage** - Specify how many records you want to retrieve per page
* **page** - Specify exactly what page you want to retrieve

## FetchBulkChargeBatch
```php
// Class

Coderatio\PaystackMirror\Actions\BulkCharges\FetchBulkChargeBatch::class

```
This action retrieves a specific batch code. It also returns useful information on its progress by way of the `total_charges` and `pending_charges` attributes.  

**Path Params**

* **id_or_code** (required) - An ID or code for the transfer whose details you want to retrieve.

## FetchChargesBatch
```php
// Class

Coderatio\PaystackMirror\Actions\BulkCharges\FetchChargesBatch::class

```
This action retrieves the charges associated with a specified batch code. Pagination parameters are available. You can also filter by status. Charge statuses can be `pending`, `success` or `failed`.

**Path Params**

* **id_or_code** (required) - An ID or code for the batch whose charges you want to retrieve.

**Query Params**

* **status** - pending, success or failed
* **perPage**
* **page**

_This params should be sent as one though._

## PauseBulkChargeBatch
```php
// Class

Coderatio\PaystackMirror\Actions\BulkCharges\PauseBulkChargeBatch::class

```
Use this action to pause processing a batch 

**Path Params**

* **batch_code** (required)

## ResumeBulkChargeBatch
```php
// Class 
Coderatio\PaystackMirror\Actions\BulkCharges\ResumeBulkChargeBatch::class

```
Use this action to resume processing a batch

**Path Params**

* **batch_code** (required)

[Read more here...](https://developers.paystack.co/v1.0/reference#initiate-bulk-charge)
___