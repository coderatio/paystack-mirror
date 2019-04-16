<?php


namespace Coderatio\PaystackMirror\Actions\BulkCharges;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ListBulkChargeBatches extends Action
{
    protected $url = 'https://api.paystack.co/bulkcharge';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}
