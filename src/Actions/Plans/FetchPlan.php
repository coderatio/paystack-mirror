<?php


namespace Coderatio\PaystackMirror\Actions\Plans;


use Coderatio\PaystackMirror\Actions\Action;
use Coderatio\PaystackMirror\Services\CurlService;
use Coderatio\PaystackMirror\Traits\HasIdOrPlanCode;
use Exception;

class FetchPlan extends Action
{
    use HasIdOrPlanCode;

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
        return $this->url . 'plan/' . $this->getIdOrPlanCode();
    }
}
