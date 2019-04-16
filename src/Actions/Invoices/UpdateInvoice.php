<?php


namespace Coderatio\PaystackMirror\Actions\Invoices;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasInvoiceIdOrCode;
use Exception;

class UpdateInvoice extends Action
{
    use HasInvoiceIdOrCode;

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->put($this->getUrl(), $this->getData());
    }

    public function getUrl(): string
    {
        return $this->url . 'paymentrequest/' . $this->getInvoiceIdOrCode();
    }
}
