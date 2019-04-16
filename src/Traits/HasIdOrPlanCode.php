<?php


namespace Coderatio\PaystackMirror\Traits;


trait HasIdOrPlanCode
{
    protected function getIdOrPlanCode()
    {
        $data = [];

        if (is_int($this->data)) {
            $data['id'] = $this->data;
        }

        if (is_string($this->data)) {
            $data['plan_code'] = $this->data;
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        if (isset($this->data['plan_code'], $this->data['id'])) {
            $this->data['plan_code'] = null;
        }

        return $this->data['plan_code'] ?? $this->data['id'] ?? null;
    }
}
