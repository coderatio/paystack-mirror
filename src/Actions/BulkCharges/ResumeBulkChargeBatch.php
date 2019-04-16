<?php


namespace Coderatio\PaystackMirror\Actions\BulkCharges;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasBulkChargeBatchIdOrCode;
use Exception;

class ResumeBulkChargeBatch extends Action
{
    use HasBulkChargeBatchIdOrCode;

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
        return $this->url . 'bulkcharge/resume/' . $this->getBulkChargeBatchIdOrCode();
    }
}
