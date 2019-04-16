<?php


namespace Coderatio\PaystackMirror\Actions\Verifications;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ResolvePhoneNumber extends Action
{
    protected $url = 'https://api.paystack.co/verifications';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }
}