<?php


namespace Coderatio\PaystackMirror\Actions\SubAccounts;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasIdOrSlug;
use Exception;

class FetchSubAccount extends Action
{
    use HasIdOrSlug;

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->getUrl(), $this->data);
    }

    public function getUrl() : string
    {
        return $this->url . 'subaccount/' . $this->getIdOrSlug();
    }
}
