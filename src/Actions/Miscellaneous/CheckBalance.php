<?php


namespace Coderatio\PaystackMirror\Actions\Miscellaneous;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class CheckBalance extends Action
{
    protected $url = 'https://api.paystack.co/balance';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}
