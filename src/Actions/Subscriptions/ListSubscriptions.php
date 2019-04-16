<?php


namespace Coderatio\PaystackMirror\Actions\Subscriptions;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ListSubscriptions extends Action
{
    protected $url = 'https://api.paystack.co/subscription';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}
