<?php


namespace Coderatio\PaystackMirror\Actions\BulkCharges;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class InitializeBulkCharge extends Action
{
    protected $url = 'https://api.paystack.co/bulkcharge';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }
}
