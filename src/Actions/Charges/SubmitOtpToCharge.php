<?php


namespace Coderatio\PaystackMirror\Actions\Charges;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class SubmitOtpToCharge extends Action
{
    protected $url = 'https://api.paystack.co/charge/submit_otp';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }
}
