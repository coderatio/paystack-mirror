<?php


namespace Coderatio\PaystackMirror\Actions\Customers;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasIdOrCustomerCode;
use Exception;

class UpdateCustomer extends Action
{
    use HasIdOrCustomerCode;

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
        return $this->url . 'customer/' . $this->getIdOrCustomerCode();
    }
}
