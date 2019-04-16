<?php


namespace Coderatio\PaystackMirror\Actions\Invoices;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class CreateInvoice extends Action
{
    protected $url = 'https://api.paystack.co/paymentrequest';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }
}
