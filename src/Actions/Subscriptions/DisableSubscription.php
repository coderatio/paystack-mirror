<?php


namespace Coderatio\PaystackMirror\Actions\Subscriptions;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class DisableSubscription extends Action
{
    protected $url = 'https://api.paystack.co/subscription/disable';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }
}
