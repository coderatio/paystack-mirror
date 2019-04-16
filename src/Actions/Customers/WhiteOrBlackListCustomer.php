<?php


namespace Coderatio\PaystackMirror\Actions\Customers;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class WhiteOrBlackListCustomer extends Action
{
    protected $url = 'https://api.paystack.co/customer/set_risk_action';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }
}
