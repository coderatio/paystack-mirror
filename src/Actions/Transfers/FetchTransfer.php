<?php


namespace Coderatio\PaystackMirror\Actions\Transfers;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class FetchTransfer extends Action
{
    protected $url = 'https://api.paystack.co/transfer';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}
