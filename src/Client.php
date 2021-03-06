<?php

namespace GingerPluginSdk;

use Ginger\ApiClient;
use Ginger\Ginger;
use GingerPluginSdk\Collections\IdealIssuers;
use GingerPluginSdk\Entities\Issuer;
use GingerPluginSdk\Entities\Order;
use GingerPluginSdk\Exceptions\APIException;
use GingerPluginSdk\Exceptions\CaptureFailedException;
use GingerPluginSdk\Exceptions\InvalidOrderDataException;
use GingerPluginSdk\Exceptions\InvalidOrderStatusException;
use GingerPluginSdk\Exceptions\OrderNotFoundException;
use GingerPluginSdk\Exceptions\RefundFailedException;
use GingerPluginSdk\Helpers\HelperTrait;
use GingerPluginSdk\Interfaces\AbstractCollectionContainerInterface;
use GingerPluginSdk\Interfaces\ArbitraryArgumentsEntityInterface;
use GingerPluginSdk\Properties\ClientOptions;
use GingerPluginSdk\Properties\Currency;
use RuntimeException;

class Client
{
    use HelperTrait;

    const PROPERTIES_PATH = "GingerPluginSdk\Properties\\";
    const COLLECTIONS_PATH = "GingerPluginSdk\Collections\\";
    const ENTITIES_PATH = "GingerPluginSdk\Entities\\";

    const MULTI_CURRENCY_CACHE_FILE_PATH = __DIR__ . "/Assets/payment_method_currencies.json";
    const CA_CERT_FILE_PATH = __DIR__ . '/Assets/cacert.pem';
    protected ApiClient $api_client;

    /**
     * @param \GingerPluginSdk\Properties\ClientOptions $options
     */
    public function __construct(ClientOptions $options)
    {
        $this->api_client = $this->createClient(
            $options->apiKey,
            $options->useBundle,
            $options->endpoint
        );

    }

    /**
     * Retrieves APIClient from original ginger-php package.
     *
     * @return \Ginger\ApiClient
     */
    public function getApiClient(): ApiClient
    {
        return $this->api_client;
    }

    /**
     * Retrieve orders for API.
     * Returns an Order Entity object.
     *
     * @throws \Exception
     */
    public function getOrder(string $id): Order
    {
        try {
            $api_order = $this->api_client->getOrder(
                id: $id
            );
        } catch (\Exception) {
            throw new OrderNotFoundException();
        }
        return self::fromArray(
            Order::class,
            $api_order
        );
    }

    /**
     * Capturing order transactions.
     * Capturing is a process of capture finances on bank account after order shipping.
     * Only completed order could be captured.
     * Only orders with supporting capturing payment methods is allowed, for example klarna-pay-later or afterpay.
     *
     * @throws \GingerPluginSdk\Exceptions\CaptureFailedException
     * @throws \GingerPluginSdk\Exceptions\InvalidOrderStatusException
     */
    public function captureOrderTransaction(string $id): bool
    {
        $order = $this->getOrder(id: $id);

        if ($order->getStatus()->get() !== 'completed') {
            throw new InvalidOrderStatusException(
                actual: $order->getStatus()->get(), expected: 'completed'
            );
        }

        try {
            $this->api_client->captureOrderTransaction(
                orderId: $id,
                transactionId: $order->getCurrentTransaction()->getId()->get()
            );
        } catch (\Exception $exception) {
            throw new CaptureFailedException($exception->getMessage());
        }

        return true;
    }

    /**
     * Converting array to object.
     * Returns new object using instance - $className and providing properties from $data to it.
     *
     * @param string $className
     * @param array $data
     * @return object
     * @throws \Exception
     *
     * @phpstan-template Q
     * @phpstan-param class-string<Q> $className
     * @phpstan-return Q
     */
    public static function fromArray(string $className, array $data): mixed
    {
        $arguments = [];
        foreach ($data as $property_name => $value) {
            if (is_array($value)) {
                if (!self::isAssoc($value)) {
                    $collection_name = self::COLLECTIONS_PATH . self::dashesToCamelCase($property_name, true);
                    $promise = [];
                    $item_type = $collection_name::ITEM_TYPE;
                    foreach ($value as $item) {
                        if (is_array($item)) {
                            array_push($promise, self::fromArray($item_type, $item));
                        } else {
                            array_push($promise, $item);
                        }
                    }
                    $arguments[self::dashesToCamelCase($property_name)] = new $collection_name(...$promise);
                } elseif (array_key_exists(AbstractCollectionContainerInterface::class, class_implements($className))) {
                    $arguments[] = self::fromArray($className::ITEM_TYPE, $value);
                } else {
                    $camel_property_name = self::dashesToCamelCase($property_name);
                    $path_to_property = self::ENTITIES_PATH . self::dashesToCamelCase($property_name, true);;
                    $arguments[$camel_property_name] = self::fromArray($path_to_property, $value);
                }
            } else {
                $camel_property_name = self::dashesToCamelCase($property_name);
                //Check if this property has a pattern validation
                $path_to_property = self::PROPERTIES_PATH . self::dashesToCamelCase($property_name, true);
                if (class_exists($path_to_property)) {
                    $arguments[$camel_property_name] = new $path_to_property($value);
                } else {
                    if (array_key_exists(ArbitraryArgumentsEntityInterface::class, class_implements($className))) {
                        $arguments[] = [$property_name => $value];
                    } else {
                        $arguments[$camel_property_name] = $value;
                    }
                }
            }
        }

        try {
            return new $className(...$arguments);
        } catch (\Error $exception) {
            throw new \Exception(sprintf("Error occurs while try to initialize %s class, result: %s", $className, $exception->getMessage()));
        }
    }

    /**
     * Initialize SDK client to use all features through it.
     *
     * @param $apiKey
     * @param $useBundle
     * @param $endpoint
     * @return \Ginger\ApiClient
     */
    private function createClient($apiKey, $useBundle, $endpoint): ApiClient
    {
        return Ginger::createClient(
            $endpoint,
            $apiKey,
            $useBundle ?
                [
                    CURLOPT_CAINFO => self::CA_CERT_FILE_PATH
                ] : []
        );
    }

    /**
     * Methods checks if the payment method is available for the selected currency.
     * The currency list will be retrieved from API or from the cached currency list.
     *
     * @param string $payment_method_name in format without bank label, just `ideal` or `apple-pay`
     * @param \GingerPluginSdk\Properties\Currency $currency
     * @return bool true if method is available / false if creating order with selected payment method and currency is not supporting
     */
    public function checkAvailabilityForPaymentMethodUsingCurrency(string $payment_method_name, Currency $currency): bool
    {
        $file_content = "";

        if (file_exists(self::MULTI_CURRENCY_CACHE_FILE_PATH)) {
            $file_content = json_decode(current(file(self::MULTI_CURRENCY_CACHE_FILE_PATH)));
        }

        if (empty($file_content) || $file_content->expiration_time <= time()) {
            $std = new \stdClass();
            $std->expiration_time = time() + (60 * 6);
            $std->currency_list = $this->api_client->getCurrencyList();
            file_put_contents(filename: self::MULTI_CURRENCY_CACHE_FILE_PATH, data: json_encode($std));
        }

        $currency_list = json_decode(current(file(self::MULTI_CURRENCY_CACHE_FILE_PATH)))->currency_list;

        return in_array($currency->get(), $currency_list->payment_methods->$payment_method_name->currencies);
    }

    /**
     * Remove file which is used for store cached multi-currency.
     * Basically, that action will be needed if users want to update the existing currency array.
     */
    public function removeCachedMultiCurrency()
    {
        unlink(self::MULTI_CURRENCY_CACHE_FILE_PATH);
    }

    /**
     * Retrieving ideal issuers.
     * Returns collection of issuers entity.
     *
     * @return \GingerPluginSdk\Collections\IdealIssuers
     * @throws \Exception
     */
    public function getIdealIssuers(): IdealIssuers
    {
        $response = new IdealIssuers();
        foreach ($this->api_client->getIdealIssuers() as $issuer) {
            $response->addIssuer(
                item: self::fromArray(Issuer::class, $issuer)
            );
        }
        return $response;
    }

    /**
     * Refund order using order lines.
     * Only completed orders could be refunded.
     *
     * @param string $order_id
     * @return array
     * @throws \GingerPluginSdk\Exceptions\InvalidOrderStatusException
     * @throws \GingerPluginSdk\Exceptions\RefundFailedException
     */
    public function refundOrder(string $order_id)
    {
        $order = $this->getOrder(id: $order_id);

        if ($order->getStatus()->get() != "completed") {
            throw new InvalidOrderStatusException($order->getStatus()->get(), 'completed');
        }

        if (!$order->getCurrentTransaction()->isCaptured()) {
            throw  new RefundFailedException('Order is not yet captured, only captured order could be refunded');
        }

        return $this->api_client->refundOrder(id: $order_id, orderData: ['order_lines' => $order->getOrderLines()->toArray()]);
    }

    /**
     * Send POST request for API to create an order resource.
     * If order data is invalid `InvalidOrderDataException` will be thrown
     *
     * @param Order $order
     * @return Order
     * @throws \GingerPluginSdk\Exceptions\InvalidOrderDataException
     * @throws \GingerPluginSdk\Exceptions\APIException
     */
    public function sendOrder(Order $order): Order
    {
        try {
            $response = $this->api_client->createOrder(
                $order->toArray()
            );
            if ($response["status"] == 'error') {
                throw new InvalidOrderDataException($response["reason"]);
            }

            return self::fromArray(
                Order::class,
                $response
            );
        } catch (RuntimeException $exception) {
            throw new APIException($exception->getMessage());
        }
    }

    public function updateOrder(Order $order): Order
    {
        $order = $this->api_client->updateOrder(
            id: $order->getId()->get(),
            orderData: $order->toArray()
        );
        return self::fromArray(Order::class, $order);
    }
}