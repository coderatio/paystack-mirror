<?php


namespace Coderatio\PaystackMirror\Actions\Refunds;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasIdOrReference;
use Exception;

class FetchRefund extends Action
{
    use HasIdOrReference;

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->getUrl(), $this->data);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url . 'refund/' . $this->getIdOrReference();
    }
}