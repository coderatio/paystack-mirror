<?php


namespace Coderatio\PaystackMirror\Actions\Pages;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class CreatePage extends Action
{
    protected $url = 'https://api.paystack.co/page';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }
}
