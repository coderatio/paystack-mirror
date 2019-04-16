<?php


namespace Coderatio\PaystackMirror\Actions\SubAccounts;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class CreateSubAccount extends Action
{
    protected $url = 'https://api.paystack.co/subaccount';

    /**
     * @param CurlService $curlService
     * @return void
     * @throws Exception
     */
    public function handle(CurlService $curlService) : void
    {
        $curlService->post($this->url, $this->getData());
    }
}
