<?php


namespace Coderatio\PaystackMirror\Actions\ControlPanel;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class UpdatePaymentSessionTimeout extends Action
{
    protected $url = 'https://api.paystack.co/integration/payment_session_timeout';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $data = [];

        if (!is_array($this->data)) {
            $data['timeout'] = $this->data;
        }

        $this->data = $data;

        $curlService->put($this->url, $this->getData());
    }
}
