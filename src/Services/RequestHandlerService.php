<?php


namespace Coderatio\PaystackMirror\Services;


use Coderatio\PaystackMirror\Actions\Action;

class RequestHandlerService
{
    public static function handle($secretKey, Action $action) : CurlHttpResponseService
    {
        $curlService = new CurlService();

        $curlService->appendRequestHeaders([
            ['Authorization', 'Bearer ' . $secretKey],
            ['Content-Type', 'application/json']
        ]);

        $action->handle($curlService);

        return $curlService->getResponse();
    }
}
