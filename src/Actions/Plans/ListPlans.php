<?php


namespace Coderatio\PaystackMirror\Actions\Plans;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ListPlans extends Action
{
    protected $url = 'https://api.paystack.co/plan';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}
