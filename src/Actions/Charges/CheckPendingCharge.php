<?php


namespace Coderatio\PaystackMirror\Actions\Charges;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasIdOrReference;
use Exception;

class CheckPendingCharge extends Action
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

    public function getUrl(): string
    {
        return $this->url . 'charge/' . $this->getIdOrReference();
    }
}
