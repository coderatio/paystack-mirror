<?php


namespace Coderatio\PaystackMirror\Actions\ControlPanel;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class FetchPaymentSessionTimeout extends Action
{
    protected $url = 'https://api.paystack.co/integration/payment_session_timeout';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url, $this->data);
    }
}
