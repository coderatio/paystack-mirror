<?php


namespace Coderatio\PaystackMirror\Traits;


trait HasInvoiceIdOrCode
{
    protected function getInvoiceIdOrCode()
    {
        $data = [];

        if (is_int($this->data)) {
            $data['id'] = $this->data;
        }

        if (is_string($this->data)) {
            $data['request_code'] = $this->data;
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        if (isset($this->data['request_code'], $this->data['id'])) {
            $this->data['request_code'] = null;
        }

        return $this->data['request_code'] ?? $this->data['id'] ?? null;
    }
}
