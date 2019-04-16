<?php

use Coderatio\PaystackMirror\Actions\Transactions\InitializeTransaction;
use Coderatio\PaystackMirror\Actions\Transactions\ListTransactions;
use Coderatio\PaystackMirror\Events\SubscriptionCreated;
use Coderatio\PaystackMirror\PaystackMirror;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Services\ParamsBuilder;

require_once 'vendor/autoload.php';

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

$curlService = new CurlService();
try {
    $curlService->post('http://paystack-mirror.test/events.php', ['contents' => $data]);
    $input = json_decode($curlService->getResponse())->contents;
    $input = json_decode($input);
} catch (Exception $e) {

}


$key = 'sk_test_995ba53eb1c211b36a1d24c209ffc3ae85d9075e';

$eventData = SubscriptionCreated::validate($key)->thenGetEvent();

$param = new ParamsBuilder();
$param->perPage = 10;
$param->page = 1;

$param1 = new ParamsBuilder();
$param1->first_name = 'Josiah';
$param1->last_name = 'Yahaya';
$param1->email = 'josiahoyahaya@gmail.com';
$param1->amount = short_naira_to_kobo('25.6k');

$account1 = new ParamsBuilder();
$account1->key = $key;
$account1->data = $param;
$cardDetails = new ParamsBuilder();
$cardDetails->number = 'xxxxxxxxxxxxxxxxxx';
$cardDetails->pin = '0982';
$cardDetails->cvv = '085';
$cardDetails->expire_year = '20';
$cardDetails->expire_month = '05';
$account1->card = $cardDetails;


exit(var_dump($account1));

$mirror = PaystackMirror::setAccounts([$account1])
    ->mirrorMultipleAccountsOn(new InitializeTransaction($param1));

foreach ($mirror as $item) {
    echo "<h3>{$item->account}</h3>";
    echo $item->response;
}