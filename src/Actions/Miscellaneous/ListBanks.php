<?php


namespace Coderatio\PaystackMirror\Actions\Miscellaneous;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Exception;

class ListBanks extends Action
{
    protected $url = 'https://api.paystack.co/bank';

    protected $requestType = 'get';

    /**
     * @param CurlService $curlService
     * @throws Exception
     */
    public function handle(CurlService $curlService): void
    {
        $curlService->get($this->url);
    }
}
