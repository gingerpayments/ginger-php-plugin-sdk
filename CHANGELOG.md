# 1.0

Initial version

## 1.1

* Implemented `fromArray` method.
* Implemented CHANGELOG.
* Implemented `FromArrayTest`.
* Implemented `dashToCamelCase` and `camelCaseToDash` method to support field name parsing.
* Implemented `ToCamelFromCamelTest`
* Implemented `ArbitraryArgumentsEntityInterface` to handle entities with dynamic constructor options list.
* Implemented new properties for `Transaction` entity :
    * id
    * merchant_id
    * created
    * modified
    * settled
    * finalized
    * completed
    * expiration_period
    * currency,
    * amount,
    * balance,
    * description,
    * product_type,
    * status,
    * reason,
    * isCapturable,
    * orderId,
    * channel,
    * projectType
    * flags
    * events
      * event
        * occurred
        * noticed
        * source
        * id
        * event
* Implemented `EventsTest`.
* Implemented `EventTest`.
* Implemented `GetOrderTest`.
* Implemented `test_customer_from_api_array` to `FromArrayTest`.
* Implemented `OrderCreationFailedException`
* Implemented `Status` Property
* Implemented `StatusTest`
* Implemented `createFieldInDateTimeISO8601` method into `SingleFieldTrait`
* Implemented `AbstractCollectionTest.php`
* Updated `AbstractCollection.php`
* Refactored Collections to store `ITEM_TYPE` of included items.

## 1.2

* Implemented coverage for `captureOrderTransaction` method from `ginger-php`.
* Implemented `OrderStub` to simplify valid order creation methods.
* Implemented `CaptureOrderTransactionTest`.
* Implemented `OrderNotFoundException` and used in `getOrder` method.
* Implemented `CaptureFailedExcpetion`.
* Implemented `getStatus` and `getId` methods to Order entity.
* Refactored `sendOrder` to return `Order` entity instead of array.
* Refactored code according to `PHPStan` analyse.
* Refactored `AbstractCollection` to not include `class-string` into construct.
* Added `PHPStan` to `composer.json` to a dev environment.