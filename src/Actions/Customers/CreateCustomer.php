<?php


namespace Coderatio\PaystackMirror\Actions\Customers;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class CreateCustomer extends Action
{
    protected $url = 'https://api.paystack.co/customer';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }
}
