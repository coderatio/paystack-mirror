<?php


namespace Coderatio\PaystackMirror\Actions\Plans;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasIdOrPlanCode;
use Exception;

class UpdatePlan extends Action
{
    use HasIdOrPlanCode;

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
        return $this->url . 'plan/' . $this->getIdOrPlanCode();
    }
}
