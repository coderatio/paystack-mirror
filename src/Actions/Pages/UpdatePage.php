<?php


namespace Coderatio\PaystackMirror\Actions\Pages;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasIdOrSlug;
use Exception;

class UpdatePage extends Action
{
    use HasIdOrSlug;

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
        return $this->url . 'page/' . $this->getIdOrSlug();
    }
}
