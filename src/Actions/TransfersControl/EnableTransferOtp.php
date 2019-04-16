<?php


namespace Coderatio\PaystackMirror\Actions\TransfersControl;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class EnableTransferOtp extends Action
{
    protected $url = 'https://api.paystack.co/transfer/enable_otp';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }
}
