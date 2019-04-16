<?php


namespace Coderatio\PaystackMirror\Actions\Verifications;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasBvn;
use Exception;

class ResolveBvn extends Action
{
    use HasBvn;

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
        return $this->url . 'bank/resolve_bvn/' . $this->getBvn();
    }
}