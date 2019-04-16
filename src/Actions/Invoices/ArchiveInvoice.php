<?php


namespace Coderatio\PaystackMirror\Actions\Invoices;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasInvoiceIdOrCode;
use Exception;

class ArchiveInvoice extends Action
{
    use HasInvoiceIdOrCode;

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->getUrl(), $this->getData());
    }

    public function getUrl(): string
    {
        return $this->url . 'invoice/archive/' . $this->getInvoiceIdOrCode();
    }
}
