<?php


namespace Coderatio\PaystackMirror\Actions\Refunds;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ListRefunds extends Action
{
    protected $url = 'https://api.paystack.co/refund';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}