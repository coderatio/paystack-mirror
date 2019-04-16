<?php


namespace Coderatio\PaystackMirror\Actions\Verifications;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ResolveAccountNumber extends Action
{
    protected $url = 'https://api.paystack.co/bank/resolve';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}