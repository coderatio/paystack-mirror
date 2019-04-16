<?php


namespace Coderatio\PaystackMirror\Actions\Transactions;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ListTransactions extends Action
{
    protected $url = 'https://api.paystack.co/transaction';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}
