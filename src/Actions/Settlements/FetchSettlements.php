<?php


namespace Coderatio\PaystackMirror\Actions\Settlements;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class FetchSettlements extends Action
{
    protected $url = 'https://api.paystack.co/settlement';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}
