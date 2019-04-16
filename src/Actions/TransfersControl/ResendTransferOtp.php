<?php


namespace Coderatio\PaystackMirror\Actions\TransfersControl;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ResendTransferOtp extends Action
{
    protected $url = 'https://api.paystack.co/transfer/resend_otp';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->post($this->url, $this->getData());
    }

    public function getData()
    {
        $data = [];

        if (is_array($this->data) && !isset($this->data['reason'])) {
            $this->data['reason'] = 'transfer';
        }

        if (is_string($this->data)) {
            $data['transfer_code'] = $this->data;
            $data['reason'] = 'transfer';
        }

        $this->data = $data;

        return parent::getData();
    }
}
