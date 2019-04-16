<?php


namespace Coderatio\PaystackMirror\Actions\Invoices;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ListInvoices extends Action
{
    protected $url = 'https://api.paystack.co/paymentrequest';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}
