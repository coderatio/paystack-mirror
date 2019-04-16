<?php


namespace Coderatio\PaystackMirror\Actions\TransferRecipients;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasRecipientCodeOrId;
use Exception;

class DeleteTransferRecipient extends Action
{
    use HasRecipientCodeOrId;

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->delete($this->getUrl(), $this->data);
    }

    public function getUrl(): string
    {
        return $this->url . 'transferrecipient/' . $this->getRecipientCodeOrId();
    }
}
