<?php


namespace Coderatio\PaystackMirror\Traits;


trait HasBulkChargeBatchIdOrCode
{
    public function getBulkChargeBatchIdOrCode()
    {
        $data = [];

        if (is_int($this->data)) {
            $data['id'] = $this->data;
        }

        if (is_string($this->data)) {
            $data['batch_code'] = $this->data;
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        if (isset($this->data['batch_code'], $this->data['id'])) {
            $this->data['batch_code'] = null;
        }

        return $this->data['batch_code'] ?? $this->data['id'] ?? null;
    }
}
