<?php


namespace Coderatio\PaystackMirror\Actions\Verifications;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasCardBin;
use Exception;

class ResolveCardBin extends Action
{
    use HasCardBin;

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
        return $this->url . 'decision/bin/' . $this->getCardBin();
    }
}