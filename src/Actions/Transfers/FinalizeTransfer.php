<?php


namespace Coderatio\PaystackMirror\Actions\Transfers;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class FinalizeTransfer extends Action
{
    protected $url = 'https://api.paystack.co/transfer/finalize_transfer';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }
}
