<?php


namespace Coderatio\PaystackMirror\Actions\Invoices;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasInvoiceIdOrCode;
use Exception;

class VerifyInvoice extends Action
{
    use HasInvoiceIdOrCode;

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->getUrl(), $this->data);
    }

    public function getUrl(): string
    {
        return $this->url . 'paymentrequest/verify/' . $this->getInvoiceIdOrCode();
    }
}
