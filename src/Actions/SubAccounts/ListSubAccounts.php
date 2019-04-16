<?php


namespace Coderatio\PaystackMirror\Actions\SubAccounts;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ListSubAccounts extends Action
{
    protected $url = 'https://api.paystack.co/subaccount';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}
