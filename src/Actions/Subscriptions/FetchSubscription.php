<?php


namespace Coderatio\PaystackMirror\Actions\Subscriptions;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasIdOrSubscriptionCode;
use Exception;

class FetchSubscription extends Action
{
    use HasIdOrSubscriptionCode;

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->getUrl(), $this->data);
    }

    public function getUrl(): string
    {
        return $this->url . 'subscription/' . $this->getIdOrSubscriptionCode();
    }
}
