<?php

namespace Coderatio\PaystackMirror\Events;

use Coderatio\PaystackMirror\Services\CurlService;
use EventInterface;
use Exception;

class Event implements EventInterface
{
    public $rawContents = '';
    protected $signature = '';
    public $eventObject;
    private $isValidOwner = false;
    protected $data = [];
    public const PAYSTACK_SIGNATURE_KEY = 'HTTP_X_PAYSTACK_SIGNATURE';

    public function __construct() {}

    /**
     * Prepare event variables
     *
     * @return Event
     */
    public static function capture(): Event
    {
        $event = new self();
        $input = json_decode($event->simulatedData()->asJson())->contents;

        //$input ;
        $event->rawContents = @file_get_contents('php://input');
        //$event->simulatedData()->headers['HTTP_X_PAYSTACK_SIGNATURE']
        $event->signature = ($_SERVER[self::PAYSTACK_SIGNATURE_KEY] ?? '');
        $event->getObject();

        return $event;
    }

    /**
     * Validate or check for valid Paystack secret key or keys.
     *
     * @param string|array $keyOrKeys
     * @return $this
     */
    public function thenValidate($keyOrKeys): self
    {
        if (is_array($keyOrKeys)) {
            foreach ($keyOrKeys as $key) {
                if ($this->isValidSignature($key)) {
                    $this->isValidOwner = true;
                }
            }
        }

        if (!is_array($keyOrKeys) && $this->isValidSignature($keyOrKeys)) {
            $this->isValidOwner  = true;
        }

        return $this;
    }

    /**
     * Load only data for an an event.
     *
     * @param $event
     * @return Event
     */
    public function thenListenOn($event): self
    {
        if (!$this->eventObject || !property_exists($this->eventObject, 'data')) {
            $this->data = [];
        }

        if ($this->isValidOwner && $event == $this->eventObject->event) {
            $this->data = $this->getObject()->data;
        }

       return $this;
    }

    /**
     * Get the event object or property from an event object
     *
     * @param string $property
     * @return object|bool|string
     */
    public function thenGetEvent($property = '')
    {
        if (!empty($this->data)) {
            if (!empty($property) && property_exists($this->eventObject, $property)) {
                return $this->eventObject->{$property};
            }

            return $this->getObject();
        }

        return false;
    }

    /**
     * Get the data object or property from an event object
     *
     * @param string $property
     * @return object|bool|string
     */
    public function thenGetData($property = '')
    {
        if (!empty($this->data)) {
            if (!empty($property) && property_exists($this->eventObject->data, $property)) {
                return $this->eventObject->data->{$property};
            }

            return $this->eventObject->data;
        }

        return false;
    }

    /**
     * Forwards the event data to another webhook.
     *
     * @param $url
     * @param array $moreHeaders
     * @param string $method
     * @return mixed
     * @throws Exception
     */
    public function thenForwardTo($url, array $moreHeaders = [], $method = 'POST')
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        if (!empty($this->data)) {
            $method = mb_strtolower($method);

            $curlService = new CurlService();
            $requestHeaders = $moreHeaders;
            $requestHeaders['X-Paystack-Signature'] = $this->signature;
            $requestHeaders['HTTP_X_PAYSTACK_SIGNATURE'] = $this->signature;
            $requestHeaders['Content-Type'] = 'application/json';
            $curlService->appendRequestHeaders($requestHeaders);

            return $curlService->$method($url, [
                'event' => $this->eventObject->event,
                'data' => json_encode($this->eventObject->data)
            ]);
        }

        return false;
    }

    /**
     * Get the event content as object
     *
     * @return mixed
     */
    protected function getObject()
    {
        return $this->eventObject = json_decode($this->rawContents);
    }

    /**
     * Check if the request has a valid signature
     *
     * @param $key
     * @return bool
     */
    protected function isValidSignature($key): bool
    {
        return $this->signature === hash_hmac('sha512', $this->rawContents, $key);
    }

    protected function simulatedData()
    {
        $data = '
{
  "event": "subscription.create",
  "data": {
    "domain": "test",
    "status": "active",
    "subscription_code": "SUB_vsyqdmlzble3uii",
    "amount": 50000,
    "cron_expression": "0 0 28 * *",
    "next_payment_date": "2016-05-19T07:00:00.000Z",
    "open_invoice": null,
    "createdAt": "2016-03-20T00:23:24.000Z",
    "plan": {
      "name": "Monthly retainer",
      "plan_code": "PLN_gx2wn530m0i3w3m",
      "description": null,
      "amount": 50000,
      "interval": "monthly",
      "send_invoices": true,
      "send_sms": true,
      "currency": "NGN"
    },
    "authorization": {
      "authorization_code": "AUTH_96xphygz",
      "bin": "539983",
      "last4": "7357",
      "exp_month": "10",
      "exp_year": "2017",
      "card_type": "MASTERCARD DEBIT",
      "bank": "GTBANK",
      "country_code": "NG",
      "brand": "MASTERCARD"
    },
    "customer": {
      "first_name": "BoJack",
      "last_name": "Horseman",
      "email": "bojack@horsinaround.com",
      "customer_code": "CUS_xnxdt6s1zg1f4nx",
      "phone": "",
      "metadata": {},
      "risk_action": "default"
    },
    "created_at": "2016-10-01T10:59:59.000Z"
  }
}';

        $curlService = new \Coderatio\PaystackMirror\Services\CurlService();
        try {
            $curlService->post('http://paystack-mirror.test/events.php', ['contents' => $data]);
        } catch (Exception $e) {
        }
        return $curlService->getResponse();
    }

}