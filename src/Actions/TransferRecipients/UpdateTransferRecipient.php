<?php


namespace Coderatio\PaystackMirror\Actions\TransferRecipients;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasRecipientCodeOrId;
use Exception;

class UpdateTransferRecipient extends Action
{
    use HasRecipientCodeOrId;

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->put($this->getUrl(), $this->getData());
    }

    public function getUrl(): string
    {
        return $this->url . 'transferrecipient/' . $this->getRecipientCodeOrId();
    }
}
